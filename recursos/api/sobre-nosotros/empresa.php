<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';

$row = $conexion->query(
    "SELECT descripcion, mision, vision, anio_fundacion FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

echo json_encode(['success' => true, 'data' => $row]);
