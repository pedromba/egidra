<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

$stmt = $conexion->prepare("DELETE FROM servicios WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['estado' => true, 'mensaje' => 'Servicio eliminado.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'No se pudo eliminar el servicio.']);
}
