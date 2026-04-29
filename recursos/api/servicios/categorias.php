<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$cats = $conexion->query(
    "SELECT id, nombre, slug, descripcion_breve, icono
     FROM categorias_servicios
     WHERE activo = 1
     ORDER BY orden ASC"
)->fetch_all(MYSQLI_ASSOC);

foreach ($cats as &$cat) {
    $stmt = $conexion->prepare(
        "SELECT titulo, descripcion_breve, icono, url_imagen_principal, es_destacado
         FROM servicios
         WHERE categoria_id = ? AND activo = 1
         ORDER BY orden ASC"
    );
    $stmt->bind_param('i', $cat['id']);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($rows as &$s) {
        if (!empty($s['url_imagen_principal']))
            $s['imagen_url'] = RUTA_BASE . $s['url_imagen_principal'];
    }
    $cat['servicios'] = $rows;
}

echo json_encode(['success' => true, 'data' => $cats]);
