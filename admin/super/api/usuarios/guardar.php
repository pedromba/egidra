<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';

$id     = isset($_POST['id'])     ? (int) $_POST['id']           : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre'])        : '';
$email  = isset($_POST['email'])  ? trim($_POST['email'])         : '';
$pass   = isset($_POST['pass'])   ? $_POST['pass']                : '';
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
    // Prevent self-demotion/deletion accidental role change? (allowed but warn would be in JS)
    if ($pass !== '') {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare(
            "UPDATE usuarios SET nombre=?, email=?, contrasena_hash=?, rol=?, estado=?
             WHERE id_usuario=?"
        );
        $stmt->bind_param('sssssi', $nombre, $email, $hash, $rol, $estado, $id);
    } else {
        $stmt = $conexion->prepare(
            "UPDATE usuarios SET nombre=?, email=?, rol=?, estado=? WHERE id_usuario=?"
        );
        $stmt->bind_param('ssssi', $nombre, $email, $rol, $estado, $id);
    }
} else {
    if ($pass === '') {
        echo json_encode(['estado' => false, 'mensaje' => 'La contraseña es obligatoria al crear.']);
        exit;
    }
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare(
        "INSERT INTO usuarios (nombre, email, contrasena_hash, rol, estado) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('sssss', $nombre, $email, $hash, $rol, $estado);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'usuarios', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Usuario guardado.', 'id' => $nuevo_id]);
} else {
    $err = $conexion->error;
    $msg = str_contains($err, 'Duplicate') ? 'El email ya está registrado.' : 'Error al guardar.';
    echo json_encode(['estado' => false, 'mensaje' => $msg]);
}
