<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$rows  = $conexion->query("SELECT id_cliente AS id, nombre FROM clientes ORDER BY nombre ASC");
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $datos[] = $r;
}
echo json_encode(['estado' => true, 'datos' => $datos]);
