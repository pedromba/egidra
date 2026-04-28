<?php
/**
 * API Mensajes (Editor) — Leer
 * Devuelve el cuerpo completo de un mensaje y lo marca como leído.
 * Parámetro GET: id (int)
 */
header('Content-Type: application/json');

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'ID inválido.']);
    exit;
}

require_once '../../../../config/conexion.php';

// Obtener el mensaje completo
$stmt = $conexion->prepare(
    "SELECT id, nombre, email, asunto, mensaje, leido, respondido, fecha_envio
     FROM contacto WHERE id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(['estado' => false, 'mensaje' => 'Mensaje no encontrado.']);
    exit;
}

$fila = $resultado->fetch_assoc();

// Marcar como leído si aún no lo estaba
if (!(bool) $fila['leido']) {
    $upd = $conexion->prepare(
        "UPDATE contacto SET leido = 1, fecha_lectura = NOW() WHERE id = ?"
    );
    $upd->bind_param('i', $id);
    $upd->execute();
}

echo json_encode([
    'estado' => true,
    'datos'  => [
        'id'          => (int)  $fila['id'],
        'nombre'      =>        $fila['nombre'],
        'email'       =>        $fila['email'],
        'asunto'      =>        $fila['asunto'],
        'mensaje'     =>        $fila['mensaje'],
        'leido'       => true,                    // ya queda marcado como leído
        'respondido'  => (bool) $fila['respondido'],
        'fecha_envio' =>        $fila['fecha_envio'],
    ],
]);
