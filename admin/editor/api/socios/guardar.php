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

$id         = isset($_POST['id'])         ? (int)  $_POST['id']         : 0;
$nombre     = isset($_POST['nombre'])     ? trim(  $_POST['nombre'])     : '';
$desc       = isset($_POST['desc'])       ? trim(  $_POST['desc'])       : '';
$logoActual = isset($_POST['logo_actual'])? trim(  $_POST['logo_actual']): '';
$url        = isset($_POST['url_web'])    ? trim(  $_POST['url_web'])    : '';
$orden      = isset($_POST['orden'])      ? (int)  $_POST['orden']       : 0;
$activo     = isset($_POST['activo'])     ? (int)(bool) $_POST['activo'] : 1;

/* Strip base URL prefix so stored path stays relative (/img/socios/...) */
$base = rtrim(RUTA_BASE, '/');
if ($logoActual && strpos($logoActual, $base) === 0) {
    $logoActual = substr($logoActual, strlen($base));
}

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

/* ── Procesar logo ── */
$logo = $logoActual;

if (!empty($_FILES['logo']['name'])) {
    $file     = $_FILES['logo'];
    $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
    $maxBytes = 1 * 1024 * 1024; // 1 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['estado' => false, 'mensaje' => 'Error al subir el logo.']);
        exit;
    }
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Formato no permitido. Usa PNG, SVG, JPG o WEBP.']);
        exit;
    }
    if ($file['size'] > $maxBytes) {
        echo json_encode(['estado' => false, 'mensaje' => 'El logo no debe superar 1 MB.']);
        exit;
    }

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $slug     = preg_replace('/[^a-z0-9]+/', '-', strtolower($nombre));
    $filename = $slug . '-' . time() . '.' . $ext;
    $destDir  = __DIR__ . '/../../../../img/socios/';
    $destPath = $destDir . $filename;

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        echo json_encode(['estado' => false, 'mensaje' => 'No se pudo guardar el logo en el servidor.']);
        exit;
    }

    /* Eliminar logo anterior si existe y es distinto */
    if ($logoActual && strpos($logoActual, '/img/socios/') !== false) {
        $anteriorPath = __DIR__ . '/../../../../' . ltrim($logoActual, '/');
        if (file_exists($anteriorPath)) {
            @unlink($anteriorPath);
        }
    }

    $logo = '/img/socios/' . $filename;
}

if ($id > 0) {
    $stmt = $conexion->prepare(
        "UPDATE socios SET nombre=?, descripcion=?, logo=?, url_web=?, orden=?, activo=? WHERE id=?"
    );
    $stmt->bind_param('ssssiii', $nombre, $desc, $logo, $url, $orden, $activo, $id);
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO socios (nombre, descripcion, logo, url_web, orden, activo) VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param('ssssii', $nombre, $desc, $logo, $url, $orden, $activo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;
    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR', ($id > 0 ? 'Editado' : 'Creado') . ': ' . ($nombre ?? 'registro'), 'socios', $nuevo_id);
    $logoUrl = ($logo && $logo[0] === '/') ? $base . $logo : $logo;
    echo json_encode(['estado' => true, 'mensaje' => 'Socio guardado.', 'id' => $nuevo_id, 'logo' => $logoUrl]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el socio.']);
}
