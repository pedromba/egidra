<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$id      = isset($_POST['id'])               ? (int)  $_POST['id']                   : 0;
$titulo  = isset($_POST['titulo'])           ? trim(  $_POST['titulo'])               : '';
$cat_id  = isset($_POST['categoria_id'])     ? (int)  $_POST['categoria_id']          : null;
$cli_id  = isset($_POST['cliente_id'])       ? (int)  $_POST['cliente_id']            : null;
$ubic    = isset($_POST['ubicacion'])        ? trim(  $_POST['ubicacion'])             : '';
$ano     = isset($_POST['ano_finalizacion']) ? (int)  $_POST['ano_finalizacion']       : null;
$desc    = isset($_POST['descripcion'])      ? trim(  $_POST['descripcion'])           : '';
$imagen  = isset($_POST['imagen'])           ? trim(  $_POST['imagen'])                : '';
$dest    = isset($_POST['es_destacado'])     ? (int)(bool) $_POST['es_destacado']     : 0;
$activo  = isset($_POST['activo'])           ? (int)(bool) $_POST['activo']           : 1;

if ($titulo === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El título es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE proyectos
         SET titulo=?, categoria_id=?, cliente_id=?, ubicacion=?,
             ano_finalizacion=?, descripcion_tecnica=?, imagen=?, es_destacado=?, activo=?
         WHERE id_proyecto=?"
    );
    $stmt->bind_param('siisisiii i', $titulo, $cat_id, $cli_id, $ubic, $ano, $desc, $imagen, $dest, $activo, $id);
    $stmt->bind_param('siisisiii', $titulo, $cat_id, $cli_id, $ubic, $ano, $desc, $imagen, $dest, $activo);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO proyectos
         (titulo, categoria_id, cliente_id, ubicacion, ano_finalizacion,
          descripcion_tecnica, imagen, es_destacado, activo)
         VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('siisisiii', $titulo, $cat_id, $cli_id, $ubic, $ano, $desc, $imagen, $dest, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    echo json_encode(['estado' => true, 'mensaje' => 'Proyecto guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el proyecto.']);
}
