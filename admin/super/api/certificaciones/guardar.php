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

$id       = isset($_POST['id'])               ? (int) $_POST['id']                : 0;
$nombre   = isset($_POST['nombre'])           ? trim($_POST['nombre'])             : '';
$org      = isset($_POST['organismo_emisor']) ? trim($_POST['organismo_emisor'])   : '';
$desc     = isset($_POST['descripcion'])      ? trim($_POST['descripcion'])        : '';
$logo     = isset($_POST['logo'])             ? trim($_POST['logo'])               : '';
$url      = isset($_POST['url_verificacion']) ? trim($_POST['url_verificacion'])   : '';
$anio     = isset($_POST['anio_obtencion'])   ? (int) $_POST['anio_obtencion']     : null;
$vence    = isset($_POST['fecha_vencimiento']) && $_POST['fecha_vencimiento'] !== ''
            ? $_POST['fecha_vencimiento'] : null;
$publ     = isset($_POST['publicada'])        ? (int)(bool)$_POST['publicada']    : 1;
$orden    = isset($_POST['orden'])            ? (int) $_POST['orden']              : 0;

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
    $stmt->bind_param('sssssisiis', $nombre, $org, $desc, $logo, $url, $anio, $vence, $publ, $orden, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO certificaciones
         (nombre, organismo_emisor, descripcion, logo, url_verificacion,
          anio_obtencion, fecha_vencimiento, estado, orden)
         VALUES (?,?,?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('sssssisii', $nombre, $org, $desc, $logo, $url, $anio, $vence, $publ, $orden);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'certificaciones', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Certificación guardada.', 'id' => $nuevo_id]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
