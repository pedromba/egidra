<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../../config/conexion.php';
require_once '../../../../../config/logger.php';

$id      = isset($_POST['id'])       ? (int) $_POST['id']           : 0;
$valor   = isset($_POST['valor'])    ? trim($_POST['valor'])         : '';
$etiq    = isset($_POST['etiqueta']) ? trim($_POST['etiqueta'])      : '';
$icono   = isset($_POST['icono'])    ? trim($_POST['icono'])         : '';
$orden   = isset($_POST['orden'])    ? (int) $_POST['orden']         : 0;

if ($valor === '' || $etiq === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'Valor y etiqueta son obligatorios.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE estadisticas_hse SET valor=?, etiqueta=?, icono=?, orden=? WHERE id=?"
    );
    $stmt->bind_param('sssii', $valor, $etiq, $icono, $orden, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO estadisticas_hse (valor, etiqueta, icono, orden) VALUES (?,?,?,?)"
    );
    $stmt->bind_param('sssi', $valor, $etiq, $icono, $orden);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($etiq ?? 'registro'), 'estadisticas_hse', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Estadística guardada.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
