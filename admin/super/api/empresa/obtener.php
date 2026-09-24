<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';

$row = $conexion->query(
    "SELECT nombre, slogan, descripcion, mision, vision, anio_fundacion,
            logo, logo_blanco, email, telefono, direccion, ciudad, pais,
            linkedin, facebook, instagram
     FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

if ($row) {
    // URL absoluta para la vista previa (la ruta en BD es relativa a la raíz del proyecto)
    $row['logo_url']        = $row['logo']        ? RUTA_BASE . ltrim($row['logo'], '/')        : null;
    $row['logo_blanco_url'] = $row['logo_blanco'] ? RUTA_BASE . ltrim($row['logo_blanco'], '/') : null;
    echo json_encode(['estado' => true, 'datos' => $row]);
} else {
    echo json_encode(['estado' => false, 'mensaje' => 'No se encontró el registro de empresa.']);
}
