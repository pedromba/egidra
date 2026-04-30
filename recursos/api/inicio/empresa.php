<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$row = $conexion->query(
    "SELECT nombre, slogan, descripcion, email, telefono, direccion, ciudad, pais,
            logo, logo_blanco, linkedin, facebook, instagram
     FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

$img_nosotros = null;
$qImg = $conexion->query("SELECT foto FROM equipo WHERE activo=1 AND foto IS NOT NULL AND foto!='' ORDER BY orden ASC LIMIT 1");
if ($rImg = $qImg->fetch_assoc()) {
    $img_nosotros = RUTA_BASE . $rImg['foto'];
}

if ($row) {
    if (!empty($row['logo']))        $row['logo_url']        = RUTA_BASE . $row['logo'];
    if (!empty($row['logo_blanco'])) $row['logo_blanco_url'] = RUTA_BASE . $row['logo_blanco'];
    $row['img_nosotros_url'] = $img_nosotros;
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'data' => null]);
}
