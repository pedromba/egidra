<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$id       = isset($_POST['id'])       ? (int) $_POST['id']              : 0;
$nombre   = isset($_POST['nombre'])   ? trim($_POST['nombre'])           : '';
$cargo    = isset($_POST['cargo'])    ? trim($_POST['cargo'])            : '';
$bio      = isset($_POST['bio'])      ? trim($_POST['bio'])              : '';
$foto     = isset($_POST['foto'])     ? trim($_POST['foto'])             : '';
$linkedin = isset($_POST['linkedin']) ? trim($_POST['linkedin'])         : '';
$orden    = isset($_POST['orden'])    ? (int) $_POST['orden']            : 0;
$activo   = isset($_POST['activo'])   ? (int)(bool)$_POST['activo']     : 1;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE equipo SET nombre=?, cargo=?, bio=?, foto=?, linkedin=?, orden=?, activo=?
         WHERE id=?"
    );
    $stmt->bind_param('sssssiii', $nombre, $cargo, $bio, $foto, $linkedin, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO equipo (nombre, cargo, bio, foto, linkedin, orden, activo)
         VALUES (?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('sssssii', $nombre, $cargo, $bio, $foto, $linkedin, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    echo json_encode(['estado' => true, 'mensaje' => 'Miembro guardado.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
