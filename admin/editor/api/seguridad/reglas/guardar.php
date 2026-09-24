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

$id     = isset($_POST['id'])     ? (int)  $_POST['id']          : 0;
$orden  = isset($_POST['orden'])  ? (int)  $_POST['orden']       : 0;
$titulo = isset($_POST['titulo']) ? trim(  $_POST['titulo'])     : '';
$desc   = isset($_POST['desc'])   ? trim(  $_POST['desc'])       : '';
$icono  = isset($_POST['icono'])  ? trim(  $_POST['icono'])      : '';
$activo = isset($_POST['activo']) ? (int)(bool) $_POST['activo'] : 1;

if ($titulo === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El título es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE reglas_oro SET numero_orden=?, titulo=?, descripcion=?, icono=?, activo=? WHERE id_regla=?"
    );
    $stmt->bind_param('isssii', $orden, $titulo, $desc, $icono, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO reglas_oro (numero_orden, titulo, descripcion, icono, activo) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('isssi', $orden, $titulo, $desc, $icono, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($titulo ?? 'registro'), 'reglas_oro', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Regla guardada.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar la regla.']);
}
