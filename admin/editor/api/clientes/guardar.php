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

$id        = isset($_POST['id'])        ? (int)   $_POST['id']              : 0;
$nombre    = isset($_POST['nombre'])    ? trim(   $_POST['nombre'])          : '';
$iniciales = isset($_POST['iniciales']) ? strtoupper(trim($_POST['iniciales'])) : '';
$sector    = isset($_POST['sector'])    ? trim(   $_POST['sector'])          : '';
$desc      = isset($_POST['desc'])      ? trim(   $_POST['desc'])            : '';
$activo    = isset($_POST['activo'])    ? (int)(bool) $_POST['activo']       : 1;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

if ($iniciales === '') {
    $words     = preg_split('/\s+/', $nombre);
    $iniciales = strtoupper(
        substr($words[0], 0, 1) .
        (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1))
    );
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE clientes SET nombre=?, iniciales=?, sector=?, descripcion=?, activo=? WHERE id_cliente=?"
    );
    $stmt->bind_param('ssssii', $nombre, $iniciales, $sector, $desc, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO clientes (nombre, iniciales, sector, descripcion, activo) VALUES (?,?,?,?,?)"
    );
    $stmt->bind_param('ssssi', $nombre, $iniciales, $sector, $desc, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'clientes', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Cliente guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el cliente.']);
}
