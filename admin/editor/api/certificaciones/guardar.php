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

$id        = isset($_POST['id'])        ? (int)  $_POST['id']             : 0;
$nombre    = isset($_POST['nombre'])    ? trim(  $_POST['nombre'])         : '';
$organismo = isset($_POST['organismo']) ? trim(  $_POST['organismo'])      : '';
$desc      = isset($_POST['desc'])      ? trim(  $_POST['desc'])           : '';
$logo      = isset($_POST['logo'])      ? trim(  $_POST['logo'])           : '';
$url       = isset($_POST['url'])       ? trim(  $_POST['url'])            : '';
$anio      = isset($_POST['anio'])      && $_POST['anio'] !== '' ? (int) $_POST['anio'] : null;
$vencimiento = isset($_POST['vencimiento']) && $_POST['vencimiento'] !== '' ? $_POST['vencimiento'] : null;
$estado    = isset($_POST['estado'])    ? (int)(bool) $_POST['estado']     : 1;
$orden     = isset($_POST['orden'])     ? (int)  $_POST['orden']           : 0;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE certificaciones
         SET nombre=?, organismo_emisor=?, descripcion=?, logo=?, url_verificacion=?,
             anio_obtencion=?, fecha_vencimiento=?, estado=?, orden=?
         WHERE id=?"
    );
    $stmt->bind_param('sssssisiii', $nombre, $organismo, $desc, $logo, $url, $anio, $vencimiento, $estado, $orden, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO certificaciones
         (nombre, organismo_emisor, descripcion, logo, url_verificacion, anio_obtencion, fecha_vencimiento, estado, orden)
         VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('sssssisii', $nombre, $organismo, $desc, $logo, $url, $anio, $vencimiento, $estado, $orden);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'certificaciones', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Certificación guardada.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar la certificación.']);
}
