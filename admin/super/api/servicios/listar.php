<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$cats = $conexion->query(
    "SELECT id, nombre, slug, icono, descripcion_breve, orden, activo
     FROM categorias_servicios ORDER BY orden ASC, id ASC"
);

$datos = [];
while ($cat = $cats->fetch_assoc()) {
    $cat['activo'] = (bool) $cat['activo'];
    $cat['orden']  = (int)  $cat['orden'];

    $stmt = $conexion->prepare(
        "SELECT id, titulo, descripcion_breve, icono, es_destacado, orden, activo
         FROM servicios WHERE categoria_id = ? ORDER BY orden ASC, id ASC"
    );
    $stmt->bind_param('i', $cat['id']);
    $stmt->execute();
    $res = $stmt->get_result();
    $svcs = [];
    while ($s = $res->fetch_assoc()) {
        $s['es_destacado'] = (bool) $s['es_destacado'];
        $s['activo']       = (bool) $s['activo'];
        $s['orden']        = (int)  $s['orden'];
        $svcs[] = $s;
    }
    $cat['servicios'] = $svcs;
    $datos[] = $cat;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
