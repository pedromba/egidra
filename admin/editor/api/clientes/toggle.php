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
if (!$id) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

$stmt = $conexion->prepare("UPDATE clientes SET activo = NOT activo WHERE id_cliente = ?");
$stmt->bind_param('i', $id);

if (!$stmt->execute() || $stmt->affected_rows < 1) {
    echo json_encode(['estado' => false, 'mensaje' => 'No se pudo actualizar el estado.']);
    exit;
}

$stmtR = $conexion->prepare("SELECT activo FROM clientes WHERE id_cliente = ?");
$stmtR->bind_param('i', $id);
$stmtR->execute();
$nuevo = (bool) $stmtR->get_result()->fetch_assoc()['activo'];

registrar_log($conexion, $_SESSION['user_id'], 'EDITAR',
    'Estado cambiado a ' . ($nuevo ? 'Activo' : 'Inactivo'), 'clientes', $id);

echo json_encode(['estado' => true, 'activo' => $nuevo]);
