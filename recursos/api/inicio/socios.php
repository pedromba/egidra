<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$result = $conexion->query(
    "SELECT nombre, descripcion, logo, url_web FROM socios WHERE activo = 1 ORDER BY orden ASC"
);

$socios = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['logo'])) $row['logo_url'] = RUTA_BASE . $row['logo'];
    // Iniciales para avatar fallback (máx. 2 palabras)
    $palabras = array_filter(explode(' ', $row['nombre']));
    $row['iniciales'] = implode('', array_map(fn($w) => mb_strtoupper($w[0]), array_slice(array_values($palabras), 0, 2)));
    $socios[] = $row;
}

echo json_encode(['success' => true, 'data' => $socios]);
