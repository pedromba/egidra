<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$id     = isset($_POST['id'])          ? (int) $_POST['id']             : 0;
$nombre = isset($_POST['nombre'])      ? trim($_POST['nombre'])          : '';
$desc   = isset($_POST['descripcion']) ? trim($_POST['descripcion'])     : '';
$logo   = isset($_POST['logo'])        ? trim($_POST['logo'])            : '';
$url    = isset($_POST['url_web'])     ? trim($_POST['url_web'])         : '';
$orden  = isset($_POST['orden'])       ? (int) $_POST['orden']           : 0;
$activo = isset($_POST['activo'])      ? (int)(bool)$_POST['activo']    : 1;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE socios SET nombre=?, descripcion=?, logo=?, url_web=?, orden=?, activo=?
         WHERE id=?"
    );
    $stmt->bind_param('ssssiii', $nombre, $desc, $logo, $url, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO socios (nombre, descripcion, logo, url_web, orden, activo)
         VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param('ssssii', $nombre, $desc, $logo, $url, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    echo json_encode(['estado' => true, 'mensaje' => 'Socio guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
