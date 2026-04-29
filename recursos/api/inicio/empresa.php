<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$row = $conexion->query(
    "SELECT nombre, slogan, descripcion, email, telefono, direccion, ciudad, pais,
            logo, logo_blanco, linkedin, facebook, instagram
     FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

if ($row) {
    if (!empty($row['logo']))        $row['logo_url']        = RUTA_BASE . $row['logo'];
    if (!empty($row['logo_blanco'])) $row['logo_blanco_url'] = RUTA_BASE . $row['logo_blanco'];
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'data' => null]);
}
