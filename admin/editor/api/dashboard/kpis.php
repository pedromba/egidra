<?php
/**
 * API Dashboard Editor — KPIs
 * Devuelve los 4 contadores del panel: mensajes, proyectos, clientes, servicios activos.
 */
header('Content-Type: application/json');

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

require_once '../../../../config/conexion.php';

$datos = [];

// Mensajes sin leer
$res = $conexion->query("SELECT COUNT(*) AS total FROM contacto WHERE leido = 0");
$datos['mensajes_nuevos'] = (int) $res->fetch_assoc()['total'];

// Mensajes recibidos hoy (para el trend)
$res = $conexion->query("SELECT COUNT(*) AS total FROM contacto WHERE DATE(fecha_envio) = CURDATE()");
$datos['mensajes_hoy'] = (int) $res->fetch_assoc()['total'];

// Proyectos activos
$res = $conexion->query("SELECT COUNT(*) AS total FROM proyectos WHERE activo = 1");
$datos['proyectos'] = (int) $res->fetch_assoc()['total'];

// Clientes activos
$res = $conexion->query("SELECT COUNT(*) AS total FROM clientes WHERE activo = 1");
$datos['clientes'] = (int) $res->fetch_assoc()['total'];

// Servicios activos (KPI exclusivo del editor en lugar de usuarios)
$res = $conexion->query("SELECT COUNT(*) AS total FROM servicios WHERE activo = 1");
$datos['servicios_activos'] = (int) $res->fetch_assoc()['total'];

echo json_encode(['estado' => true, 'datos' => $datos]);
