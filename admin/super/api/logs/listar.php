<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$accion = isset($_GET['accion']) && $_GET['accion'] !== '' ? trim($_GET['accion']) : null;
$buscar = isset($_GET['q'])      && $_GET['q'] !== ''      ? trim($_GET['q'])      : null;

$sql = "SELECT l.id, l.accion, l.descripcion_cambio AS descripcion,
               l.tabla_afectada AS tabla, l.registro_id,
               l.direccion_ip AS ip, l.fecha_hora,
               COALESCE(u.nombre, 'Sistema') AS usuario
        FROM logs_actividad l
        LEFT JOIN usuarios u ON u.id_usuario = l.usuario_id
        WHERE 1=1";

$params = [];
$types  = '';

if ($accion !== null) {
    $sql     .= " AND l.accion = ?";
    $params[] = $accion;
    $types   .= 's';
}
if ($buscar !== null) {
    $sql     .= " AND (l.descripcion_cambio LIKE ? OR u.nombre LIKE ? OR l.tabla_afectada LIKE ?)";
    $like     = "%$buscar%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types   .= 'sss';
}

$sql .= " ORDER BY l.fecha_hora DESC LIMIT 200";

if ($params) {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conexion->query($sql);
}

$datos = [];
while ($r = $result->fetch_assoc()) {
    $datos[] = $r;
}

echo json_encode(['estado' => true, 'datos' => $datos]);
