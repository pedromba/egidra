<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$rows = $conexion->query(
    "SELECT id_cliente AS id, nombre, iniciales, sector, logo, descripcion, activo
     FROM clientes ORDER BY nombre ASC"
);

$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['activo'] = (bool) $r['activo'];
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
