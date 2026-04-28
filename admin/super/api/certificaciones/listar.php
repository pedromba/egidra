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
    "SELECT id, nombre, organismo_emisor, descripcion, logo, url_verificacion,
            anio_obtencion, fecha_vencimiento, estado AS publicada, orden
     FROM certificaciones ORDER BY orden ASC, id ASC"
);

$hoy = new DateTime();
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['publicada'] = (bool) $r['publicada'];
    $r['orden']     = (int)  $r['orden'];

    if ($r['fecha_vencimiento'] === null) {
        $r['badge'] = 'indefinida';
    } else {
        $vence = new DateTime($r['fecha_vencimiento']);
        $diff  = (int) $hoy->diff($vence)->format('%r%a'); // negative = past
        if ($diff < 0) {
            $r['badge'] = 'vencida';
        } elseif ($diff <= 60) {
            $r['badge'] = 'por_vencer';
        } else {
            $r['badge'] = 'vigente';
        }
    }
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
