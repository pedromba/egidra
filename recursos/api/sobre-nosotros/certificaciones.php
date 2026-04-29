<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$result = $conexion->query(
    "SELECT nombre, organismo_emisor, descripcion, logo, url_verificacion
     FROM certificaciones WHERE estado = 1 ORDER BY orden ASC"
);

$certs = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['logo'])) $row['logo_url'] = RUTA_BASE . $row['logo'];
    $certs[] = $row;
}

echo json_encode(['success' => true, 'data' => $certs]);
