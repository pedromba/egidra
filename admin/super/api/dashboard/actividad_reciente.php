<?php
/**
 * API Dashboard Super — Actividad reciente
 * Devuelve los últimos 5 registros del log de auditoría con el nombre del usuario.
 */
header('Content-Type: application/json');

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

require_once '../../../../config/conexion.php';

$resultado = $conexion->query(
    "SELECT l.id, l.accion, l.descripcion_cambio, l.fecha_hora,
            COALESCE(u.nombre, 'Sistema') AS nombre_usuario
     FROM logs_actividad l
     LEFT JOIN usuarios u ON l.usuario_id = u.id_usuario
     ORDER BY l.fecha_hora DESC
     LIMIT 5"
);

$actividad = [];
while ($fila = $resultado->fetch_assoc()) {
    $actividad[] = [
        'id'          => (int) $fila['id'],
        'accion'      =>       $fila['accion'],
        'descripcion' =>       $fila['descripcion_cambio'],
        'fecha_hora'  =>       $fila['fecha_hora'],
        'usuario'     =>       $fila['nombre_usuario'],
    ];
}

echo json_encode(['estado' => true, 'datos' => $actividad]);
