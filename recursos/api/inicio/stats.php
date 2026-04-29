<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';

$proyectos = (int)$conexion->query("SELECT COUNT(*) AS c FROM proyectos WHERE activo = 1")->fetch_assoc()['c'];
$clientes  = (int)$conexion->query("SELECT COUNT(*) AS c FROM clientes  WHERE activo = 1")->fetch_assoc()['c'];
$emp       = $conexion->query("SELECT anio_fundacion FROM empresa WHERE id = 1 LIMIT 1")->fetch_assoc();
$anios     = ($emp && $emp['anio_fundacion']) ? (int)(date('Y') - $emp['anio_fundacion']) : 20;

echo json_encode(['success' => true, 'data' => [
    'proyectos' => $proyectos ?: 500,
    'clientes'  => $clientes  ?: 50,
    'anios'     => $anios,
    'seguridad' => 100,
]]);
