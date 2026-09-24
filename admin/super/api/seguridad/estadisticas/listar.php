<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../../config/conexion.php';

$rows = $conexion->query(
    "SELECT id, valor, etiqueta, icono, orden FROM estadisticas_hse ORDER BY orden ASC"
);

$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['orden'] = (int) $r['orden'];
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
