<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

$stmt = $conexion->prepare(
    "SELECT p.id_proyecto, p.titulo, p.descripcion_tecnica, p.ubicacion, p.ano_finalizacion, p.imagen,
            cs.nombre AS categoria, cs.slug AS categoria_slug, cs.icono AS categoria_icono,
            cl.nombre AS cliente
     FROM proyectos p
     LEFT JOIN categorias_servicios cs ON p.categoria_id = cs.id
     LEFT JOIN clientes cl            ON p.cliente_id   = cl.id_cliente
     WHERE p.id_proyecto = ? AND p.activo = 1
     LIMIT 1"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    http_response_code(404);
    echo json_encode(['success' => false, 'mensaje' => 'Proyecto no encontrado.']);
    exit;
}

if (!empty($row['imagen'])) {
    $row['imagen_url'] = RUTA_BASE . $row['imagen'];
}

echo json_encode(['success' => true, 'data' => $row]);
