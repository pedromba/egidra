<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

// Directorio para logos
$logoDir = '../../../../img/logo/';
if (!is_dir($logoDir)) {
    mkdir($logoDir, 0755, true);
}

// Función para guardar archivo y retornar ruta
function guardarLogo($archivo, $nombre_base) {
    global $logoDir;
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_nuevo = $nombre_base . '_' . time() . '.' . strtolower($ext);
    $ruta_completa = $logoDir . $nombre_nuevo;
    
    if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        return '/img/logo/' . $nombre_nuevo;
    }
    return null;
}

// Obtener datos actuales para no perder rutas si no se suben nuevos logos
$actual = $conexion->query(
    "SELECT logo, logo_blanco FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc() ?? ['logo' => '', 'logo_blanco' => ''];

// Procesar subida de logos
$logo_path = $actual['logo'];
$logo_blanco_path = $actual['logo_blanco'];

if (isset($_FILES['logo_file'])) {
    $nueva_ruta = guardarLogo($_FILES['logo_file'], 'logo');
    if ($nueva_ruta) {
        // Eliminar logo anterior si existe
        if ($actual['logo'] && file_exists($logoDir . basename($actual['logo']))) {
            unlink($logoDir . basename($actual['logo']));
        }
        $logo_path = $nueva_ruta;
    }
}

if (isset($_FILES['logo_blanco_file'])) {
    $nueva_ruta = guardarLogo($_FILES['logo_blanco_file'], 'logo_blanco');
    if ($nueva_ruta) {
        // Eliminar logo blanco anterior si existe
        if ($actual['logo_blanco'] && file_exists($logoDir . basename($actual['logo_blanco']))) {
            unlink($logoDir . basename($actual['logo_blanco']));
        }
        $logo_blanco_path = $nueva_ruta;
    }
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
    echo json_encode(['estado' => true, 'mensaje' => 'Datos de empresa guardados.']);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar.']);
}
