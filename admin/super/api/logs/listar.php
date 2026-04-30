<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';

$accion   = isset($_GET['accion']) && $_GET['accion'] !== '' ? trim($_GET['accion']) : null;
$buscar   = isset($_GET['q'])      && $_GET['q'] !== ''      ? trim($_GET['q'])      : null;
$pagina   = max(1, (int)($_GET['pagina']   ?? 1));
$por_pag  = min(100, max(10, (int)($_GET['por_pagina'] ?? 10)));
$offset   = ($pagina - 1) * $por_pag;

$where  = "WHERE 1=1";
$params = [];
$types  = '';

if ($accion !== null) {
    $where   .= " AND l.accion = ?";
    $params[] = $accion;
    $types   .= 's';
}
if ($buscar !== null) {
    $where   .= " AND (l.descripcion_cambio LIKE ? OR u.nombre LIKE ? OR l.tabla_afectada LIKE ?)";
    $like     = "%$buscar%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types   .= 'sss';
}

$sqlBase = "FROM logs_actividad l LEFT JOIN usuarios u ON u.id_usuario = l.usuario_id $where";

// Total para paginación
$sqlCount = "SELECT COUNT(*) AS total $sqlBase";
if ($params) {
    $st = $conexion->prepare($sqlCount);
    $st->bind_param($types, ...$params);
    $st->execute();
    $total = (int)$st->get_result()->fetch_assoc()['total'];
} else {
    $total = (int)$conexion->query($sqlCount)->fetch_assoc()['total'];
}

// Datos paginados
$sql = "SELECT l.id, l.accion, l.descripcion_cambio AS descripcion,
               l.tabla_afectada AS tabla, l.registro_id,
               l.direccion_ip AS ip, l.fecha_hora,
               COALESCE(u.nombre, 'Sistema') AS usuario
        $sqlBase ORDER BY l.fecha_hora DESC LIMIT ? OFFSET ?";

$paramsData  = array_merge($params, [$por_pag, $offset]);
$typesData   = $types . 'ii';
$stmt = $conexion->prepare($sql);
$stmt->bind_param($typesData, ...$paramsData);
$stmt->execute();
$result = $stmt->get_result();

$datos = [];
while ($r = $result->fetch_assoc()) $datos[] = $r;

echo json_encode([
    'estado'     => true,
    'datos'      => $datos,
    'total'      => $total,
    'pagina'     => $pagina,
    'por_pagina' => $por_pag,
    'paginas'    => (int)ceil($total / $por_pag),
]);
