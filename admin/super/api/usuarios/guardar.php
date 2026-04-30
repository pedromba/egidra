<?php
header('Content-Type: application/json');
ini_set('display_errors', '0');
error_reporting(E_ERROR);
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';
require_once '../../../../config/mailer.php';

$id     = isset($_POST['id'])     ? (int) $_POST['id']    : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email  = isset($_POST['email'])  ? trim($_POST['email'])  : '';
$pass   = isset($_POST['pass'])   ? $_POST['pass']         : '';
$rol    = isset($_POST['rol'])    && in_array($_POST['rol'], ['Super','Editor'], true)
          ? $_POST['rol'] : 'Editor';
$estado = isset($_POST['estado']) && in_array($_POST['estado'], ['activo','inactivo'], true)
          ? $_POST['estado'] : 'activo';

if ($nombre === '' || $email === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'Nombre y email son obligatorios.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['estado' => false, 'mensaje' => 'Email inválido.']);
    exit;
}

if ($id > 0) {
    /* ── Editar usuario existente ── */
    if ($pass !== '') {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare(
            "UPDATE usuarios SET nombre=?, email=?, contrasena_hash=?, rol=?, estado=? WHERE id_usuario=?"
        );
        $stmt->bind_param('sssssi', $nombre, $email, $hash, $rol, $estado, $id);
    } else {
        $stmt = $conexion->prepare(
            "UPDATE usuarios SET nombre=?, email=?, rol=?, estado=? WHERE id_usuario=?"
        );
        $stmt->bind_param('ssssi', $nombre, $email, $rol, $estado, $id);
    }

    if ($stmt->execute()) {
        registrar_log($conexion, $_SESSION['user_id'], 'EDITAR', 'Editado: ' . $nombre, 'usuarios', $id);
        echo json_encode(['estado' => true, 'mensaje' => 'Usuario actualizado.', 'id' => $id]);
    } else {
        $err = $conexion->error;
        $msg = str_contains($err, 'Duplicate') ? 'El email ya está registrado.' : 'Error al guardar.';
        echo json_encode(['estado' => false, 'mensaje' => $msg]);
    }
} else {
    /* ── Crear usuario nuevo — contraseña auto-generada ── */
    $passPlain = generarPassword(8);
    $hash      = password_hash($passPlain, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare(
        "INSERT INTO usuarios (nombre, email, contrasena_hash, rol, estado) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('sssss', $nombre, $email, $hash, $rol, $estado);

    if (!$stmt->execute()) {
        $err = $conexion->error;
        $msg = str_contains($err, 'Duplicate') ? 'El email ya está registrado.' : 'Error al guardar.';
        echo json_encode(['estado' => false, 'mensaje' => $msg]);
        exit;
    }

    $nuevo_id = $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], 'CREAR', 'Creado: ' . $nombre, 'usuarios', $nuevo_id);

    /* ── Enviar credenciales por email ── */
    $mailEnviado = false;
    $mailError   = '';
    try {
        $mail = crearMailer();
        $mail->addAddress($email, $nombre);
        $mail->addReplyTo(MAIL_EMPRESA, MAIL_FROM_NAME);
        $mail->Subject = 'Tus credenciales de acceso — EGIDRA';
        $mail->Body    = buildPlantillaCredenciales($nombre, $email, $passPlain, $rol, 'nueva');
        $mail->AltBody = "Hola $nombre,\n\nTu cuenta en EGIDRA ha sido creada.\nEmail: $email\nContraseña: $passPlain\nRol: $rol\n\nEGIDRA";
        $mail->send();
        $mailEnviado = true;
    } catch (\Exception $e) {
        $mailError = $e->getMessage();
    }

    $msg = $mailEnviado
        ? 'Usuario creado. Credenciales enviadas a ' . $email . '.'
        : 'Usuario creado, pero no se pudo enviar el email. ' . $mailError;

    echo json_encode(['estado' => true, 'mail_ok' => $mailEnviado, 'mensaje' => $msg, 'id' => $nuevo_id]);
}
