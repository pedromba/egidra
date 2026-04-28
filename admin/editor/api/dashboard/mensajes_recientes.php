<?php
/**
 * API Dashboard Editor — Mensajes recientes
 * Devuelve los últimos 4 mensajes del formulario de contacto.
 */
header('Content-Type: application/json');

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

require_once '../../../../config/conexion.php';

$resultado = $conexion->query(
    "SELECT id, nombre, email, asunto, leido, fecha_envio
     FROM contacto
     ORDER BY fecha_envio DESC
     LIMIT 4"
);

$mensajes = [];
while ($fila = $resultado->fetch_assoc()) {
    $mensajes[] = [
        'id'          => (int)  $fila['id'],
        'nombre'      =>        $fila['nombre'],
        'email'       =>        $fila['email'],
        'asunto'      =>        $fila['asunto'],
        'leido'       => (bool) $fila['leido'],   // boolean para el badge
        'fecha_envio' =>        $fila['fecha_envio'],
    ];
}

echo json_encode(['estado' => true, 'datos' => $mensajes]);
