<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';

$rows = $conexion->query(
    "SELECT id, nombre, descripcion, logo, url_web, orden, activo
     FROM socios ORDER BY orden ASC, id ASC"
);

$base  = rtrim(RUTA_BASE, '/');
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['activo'] = (bool) $r['activo'];
    $r['orden']  = (int)  $r['orden'];
    if ($r['logo'] && $r['logo'][0] === '/') {
        $r['logo'] = $base . $r['logo'];
    }
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
