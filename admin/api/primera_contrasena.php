<?php
header('Content-Type: application/json');
ini_set('display_errors', '0');

session_start();
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

if (empty($_SESSION['primera_sesion'])) {
    echo json_encode(['estado' => false, 'mensaje' => 'La contraseña ya fue configurada.']);
    exit;
}

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../config/logger.php';

$nueva    = $_POST['nueva']    ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if (strlen($nueva) < 8) {
    echo json_encode(['estado' => false, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres.']);
    exit;
}
if ($nueva !== $confirmar) {
    echo json_encode(['estado' => false, 'mensaje' => 'Las contraseñas no coinciden.']);
    exit;
}

$hash = password_hash($nueva, PASSWORD_DEFAULT);
$id   = (int)$_SESSION['user_id'];

$stmt = $conexion->prepare(
    "UPDATE usuarios SET contrasena_hash = ?, primera_sesion = 0 WHERE id_usuario = ?"
);
$stmt->bind_param('si', $hash, $id);

if (!$stmt->execute()) {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar. Inténtalo de nuevo.']);
    exit;
}

$_SESSION['primera_sesion'] = 0;
registrar_log($conexion, $id, 'SISTEMA', 'Contraseña inicial cambiada por el usuario.', 'usuarios', $id);

echo json_encode(['estado' => true, 'mensaje' => 'Contraseña actualizada correctamente.']);
