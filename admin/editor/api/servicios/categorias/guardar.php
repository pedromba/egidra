<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../../config/conexion.php';
require_once '../../../../../config/logger.php';

$id     = isset($_POST['id'])                ? (int)  $_POST['id']                : 0;
$nombre = isset($_POST['nombre'])            ? trim(  $_POST['nombre'])            : '';
$icono  = isset($_POST['icono'])             ? trim(  $_POST['icono'])             : '';
$desc   = isset($_POST['descripcion_breve']) ? trim(  $_POST['descripcion_breve']) : '';
$orden  = isset($_POST['orden'])             ? (int)  $_POST['orden']              : 0;
$activo = isset($_POST['activo'])            ? (int)(bool) $_POST['activo']        : 1;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

$slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nombre));

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE categorias_servicios SET nombre=?, slug=?, icono=?, descripcion_breve=?, orden=?, activo=?
         WHERE id=?"
    );
    $stmt->bind_param('ssssiii', $nombre, $slug, $icono, $desc, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO categorias_servicios (nombre, slug, icono, descripcion_breve, orden, activo)
         VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param('ssssii', $nombre, $slug, $icono, $desc, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR',
        ($id > 0 ? 'Editado' : 'Creado') . ': ' . $nombre, 'categorias_servicios', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Categoría guardada.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar la categoría.']);
}
