<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../../config/conexion.php';

$id     = isset($_POST['id'])          ? (int) $_POST['id']              : 0;
$titulo = isset($_POST['titulo'])      ? trim($_POST['titulo'])           : '';
$desc   = isset($_POST['descripcion']) ? trim($_POST['descripcion'])      : '';
$icono  = isset($_POST['icono'])       ? trim($_POST['icono'])            : '';

if ($titulo === '' || $id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID y título son obligatorios.']);
    exit;
}

$stmt = $conexion->prepare(
    "UPDATE reglas_oro SET titulo=?, descripcion=?, icono=? WHERE id_regla=?"
);
$stmt->bind_param('sssi', $titulo, $desc, $icono, $id);

if ($stmt->execute()) {
    echo json_encode(['estado' => true, 'mensaje' => 'Regla actualizada.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar la regla.']);
}
