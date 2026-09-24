<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';

$result = $conexion->query(
    "SELECT numero_orden, titulo, descripcion, icono
     FROM reglas_oro
     WHERE activo = 1
     ORDER BY numero_orden ASC"
);

$reglas = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'data' => $reglas]);
