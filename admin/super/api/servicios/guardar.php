<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';

$id     = isset($_POST['id'])          ? (int) $_POST['id']              : 0;
$cat_id = isset($_POST['categoria_id'])? (int) $_POST['categoria_id']    : 0;
$titulo = isset($_POST['titulo'])      ? trim($_POST['titulo'])           : '';
$desc   = isset($_POST['descripcion']) ? trim($_POST['descripcion'])      : '';
$icono  = isset($_POST['icono'])       ? trim($_POST['icono'])            : '';
$dest   = isset($_POST['es_destacado'])? (int)(bool)$_POST['es_destacado']:0;
$orden  = isset($_POST['orden'])       ? (int) $_POST['orden']            : 0;
$activo = isset($_POST['activo'])      ? (int)(bool)$_POST['activo']     : 1;

if ($titulo === '' || $cat_id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'Título y categoría son obligatorios.']);
    exit;
}

// Generate slug from title
$slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $titulo));

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE servicios
         SET categoria_id=?, titulo=?, slug=?, descripcion_breve=?,
             icono=?, es_destacado=?, orden=?, activo=?
         WHERE id=?"
    );
    $stmt->bind_param('issssiiii', $cat_id, $titulo, $slug, $desc, $icono, $dest, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO servicios
         (categoria_id, titulo, slug, descripcion_breve, icono, es_destacado, orden, activo)
         VALUES (?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('issssiii', $cat_id, $titulo, $slug, $desc, $icono, $dest, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($titulo ?? 'registro'), 'servicios', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Servicio guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el servicio.']);
}
