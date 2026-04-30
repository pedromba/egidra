<?php
header('Content-Type: application/json');
ini_set('display_errors', '0');
error_reporting(E_ERROR);
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/logger.php';
require_once '../../../../config/rutas.php';

$id       = isset($_POST['id'])       ? (int)  $_POST['id']          : 0;
$nombre   = isset($_POST['nombre'])   ? trim(  $_POST['nombre'])      : '';
$cargo    = isset($_POST['cargo'])    ? trim(  $_POST['cargo'])       : '';
$bio      = isset($_POST['bio'])      ? trim(  $_POST['bio'])         : '';
$fotoActual = isset($_POST['foto_actual']) ? trim($_POST['foto_actual']) : '';

/* Strip base URL prefix so the stored/deletion path stays relative (/img/equipo/...) */
$base = rtrim(RUTA_BASE, '/');
if ($fotoActual && strpos($fotoActual, $base) === 0) {
    $fotoActual = substr($fotoActual, strlen($base));
}
$linkedin = isset($_POST['linkedin']) ? trim(  $_POST['linkedin'])    : '';
$orden    = isset($_POST['orden'])    ? (int)  $_POST['orden']        : 0;
$activo   = isset($_POST['activo'])   ? (int)(bool) $_POST['activo']  : 1;

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

/* ── Procesar foto ── */
$foto = $fotoActual;

if (!empty($_FILES['foto']['name'])) {
    $file     = $_FILES['foto'];
    $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxBytes = 2 * 1024 * 1024; // 2 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['estado' => false, 'mensaje' => 'Error al subir la imagen.']);
        exit;
    }
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Formato no permitido. Usa JPG, PNG, WEBP o GIF.']);
        exit;
    }
    if ($file['size'] > $maxBytes) {
        echo json_encode(['estado' => false, 'mensaje' => 'La imagen no debe superar los 2 MB.']);
        exit;
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $slug     = preg_replace('/[^a-z0-9]+/', '-', strtolower($nombre));
    $filename = $slug . '-' . time() . '.' . $ext;
    $destDir  = __DIR__ . '/../../../../img/equipo/';
    $destPath = $destDir . $filename;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        echo json_encode(['estado' => false, 'mensaje' => 'No se pudo guardar la imagen en el servidor.']);
        exit;
    }

    /* Eliminar foto anterior si existe y es distinta */
    if ($fotoActual && strpos($fotoActual, '/img/equipo/') !== false) {
        $anteriorPath = __DIR__ . '/../../../../' . ltrim($fotoActual, '/');
        if (file_exists($anteriorPath)) {
            @unlink($anteriorPath);
        }
    }

    $foto = '/img/equipo/' . $filename;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE equipo SET nombre=?, cargo=?, bio=?, foto=?, linkedin=?, orden=?, activo=? WHERE id=?"
    );
    $stmt->bind_param('sssssiii', $nombre, $cargo, $bio, $foto, $linkedin, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO equipo (nombre, cargo, bio, foto, linkedin, orden, activo) VALUES (?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('sssssii', $nombre, $cargo, $bio, $foto, $linkedin, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'equipo', $nuevo_id);
    $fotoUrl  = ($foto && $foto[0] === '/') ? $base . $foto : $foto;
    echo json_encode(['estado' => true, 'mensaje' => 'Miembro guardado.', 'id' => $nuevo_id, 'foto' => $fotoUrl]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el miembro.']);
}
