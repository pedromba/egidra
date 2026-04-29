<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';

$id     = isset($_POST['id'])     ? (int)  $_POST['id']          : 0;
$titulo = isset($_POST['titulo']) ? trim(  $_POST['titulo'])      : '';
$desc   = isset($_POST['desc'])   ? trim(  $_POST['desc'])        : '';
$icono  = isset($_POST['icono'])  ? trim(  $_POST['icono'])       : '';
$orden  = isset($_POST['orden'])  ? (int)  $_POST['orden']        : 0;
$activo = isset($_POST['activo']) ? (int)(bool) $_POST['activo']  : 1;

if ($titulo === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El título es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE valores SET titulo=?, descripcion=?, icono=?, orden=?, activo=? WHERE id=?"
    );
    $stmt->bind_param('sssiii', $titulo, $desc, $icono, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO valores (titulo, descripcion, icono, orden, activo) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('sssii', $titulo, $desc, $icono, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($titulo ?? 'registro'), 'valores', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Valor guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el valor.']);
}
