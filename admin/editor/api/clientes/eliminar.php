<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
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

$stmt = $conexion->prepare("DELETE FROM clientes WHERE id_cliente = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    registrar_log($conexion, $_SESSION['user_id'], 'ELIMINAR', 'Eliminado registro id=' . $id, 'clientes', $id);
    echo json_encode(['estado' => true, 'mensaje' => 'Cliente eliminado.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al eliminar el cliente.']);
}
