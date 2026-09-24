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

// Directorio para logos
$logoDir = '../../../../img/logo/';
if (!is_dir($logoDir)) {
    mkdir($logoDir, 0755, true);
}

// ─── Subida de logos ───
// Solo imágenes rasterizadas (SVG excluido: puede contener scripts)
const LOGO_MIME_EXT = [
    'image/png'  => 'png',
    'image/jpeg' => 'jpg',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
];
const LOGO_MAX_BYTES = 2 * 1024 * 1024;

// Devuelve la ruta relativa guardada (img/logo/...) o lanza un mensaje de error
function guardarLogo($archivo, $nombre_base) {
    global $logoDir;

    if ($archivo['error'] === UPLOAD_ERR_INI_SIZE || $archivo['error'] === UPLOAD_ERR_FORM_SIZE) {
        throw new RuntimeException('El logo supera el tamaño máximo permitido (' . ini_get('upload_max_filesize') . ').');
    }
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No se pudo subir el logo (código ' . (int)$archivo['error'] . ').');
    }
    if ($archivo['size'] > LOGO_MAX_BYTES) {
        throw new RuntimeException('El logo no puede superar 2 MB.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']);
    if (!isset(LOGO_MIME_EXT[$mime]) || @getimagesize($archivo['tmp_name']) === false) {
        throw new RuntimeException('Formato no válido. Usa PNG (recomendado, con fondo transparente), JPG, WEBP o GIF.');
    }

    $nombre_nuevo  = $nombre_base . '_' . time() . '.' . LOGO_MIME_EXT[$mime];
    $ruta_completa = $logoDir . $nombre_nuevo;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        throw new RuntimeException('No se pudo guardar el logo en el servidor (revisa permisos de img/logo/).');
    }
    // Ruta relativa sin barra inicial: se renderiza como RUTA_BASE . ruta
    return 'img/logo/' . $nombre_nuevo;
}

// Obtener datos actuales para no perder rutas si no se suben nuevos logos
$actual = $conexion->query(
    "SELECT logo, logo_blanco FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc() ?? ['logo' => '', 'logo_blanco' => ''];

// Procesar subida de logos
$logo_path = $actual['logo'];
$logo_blanco_path = $actual['logo_blanco'];

try {
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $logo_path = guardarLogo($_FILES['logo_file'], 'logo');
    }
    if (isset($_FILES['logo_blanco_file']) && $_FILES['logo_blanco_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $logo_blanco_path = guardarLogo($_FILES['logo_blanco_file'], 'logo_blanco');
    }
} catch (RuntimeException $e) {
    // Si uno de los dos falló, no dejar huérfano el que sí se subió
    foreach (['logo' => $logo_path, 'logo_blanco' => $logo_blanco_path] as $k => $ruta) {
        if ($ruta !== $actual[$k] && $ruta && file_exists($logoDir . basename($ruta))) {
            unlink($logoDir . basename($ruta));
        }
    }
    echo json_encode(['estado' => false, 'mensaje' => $e->getMessage()]);
    exit;
}

$campos = [
    'nombre'         => trim($_POST['nombre']         ?? ''),
    'slogan'         => trim($_POST['slogan']         ?? ''),
    'descripcion'    => trim($_POST['descripcion']    ?? ''),
    'mision'         => trim($_POST['mision']         ?? ''),
    'vision'         => trim($_POST['vision']         ?? ''),
    'anio_fundacion' => (int)($_POST['anio_fundacion'] ?? 0) ?: null,
    'logo'           => $logo_path,
    'logo_blanco'    => $logo_blanco_path,
    'email'          => trim($_POST['email']          ?? ''),
    'telefono'       => trim($_POST['telefono']       ?? ''),
    'direccion'      => trim($_POST['direccion']      ?? ''),
    'ciudad'         => trim($_POST['ciudad']         ?? ''),
    'pais'           => trim($_POST['pais']           ?? ''),
    'linkedin'       => trim($_POST['linkedin']       ?? ''),
    'facebook'       => trim($_POST['facebook']       ?? ''),
    'instagram'      => trim($_POST['instagram']      ?? ''),
];

if ($campos['nombre'] === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

$sets   = implode(', ', array_map(fn($k) => "$k=?", array_keys($campos)));
$tipos  = 'sssss' . 'i' . 'ssssssssss';
$valores = array_values($campos);

$stmt = $conexion->prepare("UPDATE empresa SET $sets, fecha_actualizacion=NOW() WHERE id=1");
$stmt->bind_param($tipos, ...$valores);

if ($stmt->execute()) {
    // Eliminar logos anteriores ya reemplazados
    foreach (['logo' => $logo_path, 'logo_blanco' => $logo_blanco_path] as $k => $ruta) {
        if ($actual[$k] && $ruta !== $actual[$k] && file_exists($logoDir . basename($actual[$k]))) {
            unlink($logoDir . basename($actual[$k]));
        }
    }
    // Invalidar la caché del sidebar para que el nuevo nombre/logo se vea al momento
    unset($_SESSION['empresa_data']);
    registrar_log($conexion, $_SESSION['user_id'], 'EDITAR', 'Editado: datos de empresa', 'empresa', 1);
    echo json_encode(['estado' => true, 'mensaje' => 'Datos de empresa guardados.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
