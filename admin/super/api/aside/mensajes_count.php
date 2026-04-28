<?php
header('Content-Type: application/json');
session_start();

if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'count' => 0]);
    exit;
}

require_once '../../../../config/conexion.php';

$result = $conexion->query(
    "SELECT COUNT(*) as total FROM contacto WHERE leido = FALSE"
);

$row = $result->fetch_assoc();
$count = (int)($row['total'] ?? 0);

echo json_encode(['estado' => true, 'count' => $count]);
?>
