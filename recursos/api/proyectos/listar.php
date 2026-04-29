<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

// Categorías activas para los filtros
$cats = $conexion->query(
    "SELECT id, nombre, slug, icono FROM categorias_servicios WHERE activo = 1 ORDER BY orden ASC"
)->fetch_all(MYSQLI_ASSOC);

// Proyectos con JOIN a categoría y cliente
$result = $conexion->query(
    "SELECT p.titulo, p.descripcion_tecnica, p.ubicacion, p.ano_finalizacion, p.imagen,
            cs.nombre AS categoria, cs.slug AS categoria_slug, cs.icono AS categoria_icono,
            cl.nombre AS cliente
     FROM proyectos p
     LEFT JOIN categorias_servicios cs ON p.categoria_id = cs.id
     LEFT JOIN clientes cl            ON p.cliente_id   = cl.id_cliente
     WHERE p.activo = 1
     ORDER BY p.id_proyecto DESC"
);

$proyectos = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['imagen'])) $row['imagen_url'] = RUTA_BASE . $row['imagen'];
    $proyectos[] = $row;
}

echo json_encode([
    'success'    => true,
    'categorias' => $cats,
    'data'       => $proyectos,
]);
