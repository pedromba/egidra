<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$rows = $conexion->query(
    "SELECT id, nombre, organismo_emisor, descripcion, logo, url_verificacion,
            anio_obtencion, fecha_vencimiento, estado, orden,
            CASE
                WHEN fecha_vencimiento IS NULL            THEN 'vigente'
                WHEN fecha_vencimiento < CURDATE()        THEN 'vencida'
                WHEN fecha_vencimiento < DATE_ADD(CURDATE(), INTERVAL 60 DAY) THEN 'vence'
                ELSE 'vigente'
            END AS estado_label
     FROM certificaciones ORDER BY orden ASC, id ASC"
);
$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['publicada'] = (bool) $r['estado'];
    $datos[] = $r;
}
echo json_encode(['estado' => true, 'datos' => $datos]);
