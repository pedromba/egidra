<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$result = $conexion->query(
    "SELECT nombre, iniciales, logo FROM clientes WHERE activo = 1 ORDER BY nombre ASC"
);

$clientes = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['logo'])) $row['logo_url'] = RUTA_BASE . $row['logo'];
    $clientes[] = $row;
}

echo json_encode(['success' => true, 'data' => $clientes]);
