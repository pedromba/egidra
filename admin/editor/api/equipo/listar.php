<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';

$rows  = $conexion->query(
    "SELECT id, nombre, cargo, bio, foto, linkedin, orden, activo FROM equipo ORDER BY orden ASC, id ASC"
);
$base  = rtrim(RUTA_BASE, '/');
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['activo'] = (bool) $r['activo'];
    if ($r['foto'] && $r['foto'][0] === '/') {
        $r['foto'] = $base . $r['foto'];
    }
    $datos[] = $r;
}
echo json_encode(['estado' => true, 'datos' => $datos]);
