<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$result = $conexion->query(
    "SELECT p.titulo, p.descripcion_tecnica, p.ubicacion, p.imagen,
            cs.nombre AS categoria, cs.icono
     FROM proyectos p
     LEFT JOIN categorias_servicios cs ON p.categoria_id = cs.id
     WHERE p.activo = 1 AND p.es_destacado = 1
     ORDER BY p.id_proyecto DESC
     LIMIT 3"
);

$proyectos = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['imagen'])) $row['imagen_url'] = RUTA_BASE . $row['imagen'];
    $proyectos[] = $row;
}

echo json_encode(['success' => true, 'data' => $proyectos]);
