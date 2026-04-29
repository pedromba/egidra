<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';

$rows = $conexion->query(
    "SELECT p.id_proyecto AS id, p.titulo, p.descripcion_tecnica, p.ubicacion,
            p.ano_finalizacion, p.imagen, p.es_destacado, p.activo,
            p.cliente_id, c.nombre AS cliente_nombre,
            p.categoria_id, cs.nombre AS categoria_nombre, cs.slug AS categoria_slug
     FROM proyectos p
     LEFT JOIN clientes c  ON c.id_cliente = p.cliente_id
     LEFT JOIN categorias_servicios cs ON cs.id = p.categoria_id
     ORDER BY p.ano_finalizacion DESC, p.id_proyecto DESC"
);

$datos = [];
while ($r = $rows->fetch_assoc()) {
    $r['es_destacado'] = (bool) $r['es_destacado'];
    $r['activo']       = (bool) $r['activo'];
    if (!empty($r['imagen'])) $r['imagen_url'] = RUTA_BASE . $r['imagen'];
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
