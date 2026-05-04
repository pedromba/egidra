<?php
header('Content-Type: application/json');
ini_set('display_errors', '1');
error_reporting(E_ALL);
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';
require_once '../../../../config/mailer.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

/* Obtener datos del usuario */
$row = $conexion->query("SELECT nombre, email, rol FROM usuarios WHERE id_usuario = $id LIMIT 1")->fetch_assoc();
if (!$row) {
    echo json_encode(['estado' => false, 'mensaje' => 'Usuario no encontrado.']);
    exit;
}

$passPlain = generarPassword(8);
$hash      = password_hash($passPlain, PASSWORD_DEFAULT);

$stmt = $conexion->prepare("UPDATE usuarios SET contrasena_hash=? WHERE id_usuario=?");
$stmt->bind_param('si', $hash, $id);

if (!$stmt->execute()) {
    echo json_encode(['estado' => false, 'mensaje' => 'No se pudo actualizar la contraseña.']);
    exit;
}

registrar_log($conexion, $_SESSION['user_id'], 'EDITAR', 'Contraseña reseteada para id=' . $id, 'usuarios', $id);

/* Enviar nueva contraseña por email */
$mailEnviado = false;
$mailError   = '';
try {
    $mail = crearMailer();
    $mail->addAddress($row['email'], $row['nombre']);
    $mail->addReplyTo(MAIL_EMPRESA, MAIL_FROM_NAME);
    $mail->Subject = 'Tu contraseña ha sido reseteada — EGIDRA';
    $mail->Body    = buildPlantillaCredenciales($row['nombre'], $row['email'], $passPlain, $row['rol'], 'reset');
    $mail->AltBody = "Hola {$row['nombre']},\n\nTu contraseña ha sido reseteada.\nEmail: {$row['email']}\nNueva contraseña: $passPlain\n\nEGIDRA";
    $mail->send();
    $mailEnviado = true;
} catch (\Exception $e) {
    $mailError = $e->getMessage();
}

$msg = $mailEnviado
    ? 'Contraseña reseteada y enviada a ' . $row['email'] . '.'
    : 'Contraseña reseteada, pero no se pudo enviar el email. ' . $mailError;

echo json_encode(['estado' => true, 'mail_ok' => $mailEnviado, 'mensaje' => $msg]);
