<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/rutas.php';

$result = $conexion->query(
    "SELECT nombre, cargo, bio, foto, linkedin FROM equipo WHERE activo = 1 ORDER BY orden ASC"
);

$equipo = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['foto'])) $row['foto_url'] = RUTA_BASE . $row['foto'];
    $equipo[] = $row;
}

echo json_encode(['success' => true, 'data' => $equipo]);
