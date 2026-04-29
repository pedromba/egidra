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
    // Iniciales para badge (máx. 2 letras)
    $palabras      = array_values(array_filter(explode(' ', $row['nombre'])));
    $row['iniciales'] = mb_strtoupper(
        implode('', array_map(fn($w) => $w[0], array_slice($palabras, 0, 2)))
    );
    $socios[] = $row;
}

echo json_encode(['success' => true, 'data' => $socios]);
