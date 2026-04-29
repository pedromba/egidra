<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../../config/conexion.php';

$rows  = $conexion->query(
    "SELECT id_regla AS id, numero_orden, titulo, descripcion, icono, activo
     FROM reglas_oro ORDER BY numero_orden ASC"
);
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['activo'] = (bool) $r['activo'];
    $datos[] = $r;
}
echo json_encode(['estado' => true, 'datos' => $datos]);
