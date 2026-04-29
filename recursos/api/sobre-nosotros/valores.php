<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';

$result  = $conexion->query("SELECT titulo, descripcion, icono FROM valores WHERE activo = 1 ORDER BY orden ASC");
$valores = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'data' => $valores]);
