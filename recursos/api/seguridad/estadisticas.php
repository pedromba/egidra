<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';

$result = $conexion->query(
    "SELECT valor, etiqueta, icono
     FROM estadisticas_hse
     ORDER BY orden ASC"
);

$stats = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(['success' => true, 'data' => $stats]);
