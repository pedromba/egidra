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

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM valores WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    registrar_log($conexion, $_SESSION['user_id'], 'ELIMINAR', 'Eliminado registro id=' . $id, 'valores', $id);
    echo json_encode(['estado' => true, 'mensaje' => 'Valor eliminado.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'No se pudo eliminar.']);
}
