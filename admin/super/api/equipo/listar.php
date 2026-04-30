<?php
header('Content-Type: application/json');
ini_set('display_errors', '0');
error_reporting(E_ERROR);
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';

$rows = $conexion->query(
    "SELECT id, nombre, iniciales, cargo, bio, foto, linkedin, orden, activo
     FROM equipo ORDER BY orden ASC, id ASC"
);

if (!$rows) {
    echo json_encode(['estado' => false, 'mensaje' => 'Error en la consulta: ' . $conexion->error]);
    exit;
}

$base  = rtrim(RUTA_BASE, '/');
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['activo'] = (bool) $r['activo'];
    $r['orden']  = (int)  $r['orden'];
    if ($r['foto'] && $r['foto'][0] === '/') {
        $r['foto'] = $base . $r['foto'];
    }
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
