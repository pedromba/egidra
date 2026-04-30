<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401); echo json_encode(['total' => 0]); exit;
}
require_once '../../../../config/conexion.php';
$r = $conexion->query("SELECT COUNT(*) AS c FROM contacto WHERE leido = 0");
echo json_encode(['total' => (int)($r->fetch_assoc()['c'] ?? 0)]);
