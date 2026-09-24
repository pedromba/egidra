<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401); echo json_encode(['total' => 0]); exit;
}
require_once '../../../../config/conexion.php';
$r = $conexion->query(
    "SELECT COUNT(*) AS c FROM logs_actividad
     WHERE fecha_hora >= NOW() - INTERVAL 24 HOUR AND accion != 'LOGIN'"
);
echo json_encode(['total' => (int)($r->fetch_assoc()['c'] ?? 0)]);
