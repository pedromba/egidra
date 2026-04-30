<?php
header('Content-Type: application/json');
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}
require_once '../../../../config/conexion.php';
require_once '../../../../config/rutas.php';
require_once '../../../../config/logger.php';

$id        = isset($_POST['id'])        ? (int)   $_POST['id']              : 0;
$nombre    = isset($_POST['nombre'])    ? trim(   $_POST['nombre'])          : '';
$iniciales = isset($_POST['iniciales']) ? strtoupper(trim($_POST['iniciales'])) : '';
$sector    = isset($_POST['sector'])    ? trim(   $_POST['sector'])          : '';
$desc      = isset($_POST['desc'])      ? trim(   $_POST['desc'])            : '';
$activo    = isset($_POST['activo'])    ? (int)(bool) $_POST['activo']       : 1;
$quitar    = !empty($_POST['quitar_logo']);

if ($nombre === '') {
    echo json_encode(['estado' => false, 'mensaje' => 'El nombre es obligatorio.']);
    exit;
}

if ($iniciales === '') {
    $words     = preg_split('/\s+/', $nombre);
    $iniciales = strtoupper(
        substr($words[0], 0, 1) .
        (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1))
    );
}

// ── Manejo de logo ────────────────────────────────────────────────────────────
$nuevo_logo  = null;
$logo_cambio = false;

if (!empty($_FILES['logo']['tmp_name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['logo']['tmp_name'])) {
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['logo']['tmp_name']);

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        echo json_encode(['estado' => false, 'mensaje' => 'Solo se permiten imágenes JPG, PNG o WebP.']);
        exit;
    }
    if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['estado' => false, 'mensaje' => 'El logo no puede superar 2 MB.']);
        exit;
    }

    $dir      = DIR_BASE . 'img/clientes/';
    $filename = 'cli_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $filename)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el archivo.']);
        exit;
    }
    $nuevo_logo  = 'img/clientes/' . $filename;
    $logo_cambio = true;
} elseif ($quitar) {
    $nuevo_logo  = null;
    $logo_cambio = true;
}

// ── Obtener logo anterior para borrarlo si se reemplaza ───────────────────────
$logo_anterior = null;
if ($id > 0 && $logo_cambio) {
    $stmtLogo = $conexion->prepare("SELECT logo FROM clientes WHERE id_cliente = ? LIMIT 1");
    $stmtLogo->bind_param('i', $id);
    $stmtLogo->execute();
    $rLogo = $stmtLogo->get_result();
    if ($row = $rLogo->fetch_assoc()) $logo_anterior = $row['logo'];
    $stmtLogo->close();
}

// ── Guardar en BD ─────────────────────────────────────────────────────────────
if ($id > 0) {
    if ($logo_cambio) {
        $stmt = $conexion->prepare(
            "UPDATE clientes SET nombre=?, iniciales=?, sector=?, descripcion=?, activo=?, logo=? WHERE id_cliente=?"
        );
        $stmt->bind_param('ssssisi', $nombre, $iniciales, $sector, $desc, $activo, $nuevo_logo, $id);
    } else {
        $stmt = $conexion->prepare(
            "UPDATE clientes SET nombre=?, iniciales=?, sector=?, descripcion=?, activo=? WHERE id_cliente=?"
        );
        $stmt->bind_param('ssssii', $nombre, $iniciales, $sector, $desc, $activo, $id);
    }
} else {
    $stmt = $conexion->prepare(
        "INSERT INTO clientes (nombre, iniciales, sector, descripcion, activo, logo) VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param('ssssis', $nombre, $iniciales, $sector, $desc, $activo, $nuevo_logo);
}

if ($stmt->execute()) {
    $nuevo_id = $id > 0 ? $id : $conexion->insert_id;

    // Borrar logo anterior del disco si fue reemplazado o quitado
    if ($logo_anterior && $logo_cambio) {
        $ruta_antigua = DIR_BASE . $logo_anterior;
        if (file_exists($ruta_antigua)) @unlink($ruta_antigua);
    }

    registrar_log($conexion, $_SESSION['user_id'], $id > 0 ? 'EDITAR' : 'CREAR',
        ($id > 0 ? 'Editado' : 'Creado') . ': ' . $nombre, 'clientes', $nuevo_id);
    echo json_encode(['estado' => true, 'mensaje' => 'Cliente guardado.', 'id' => $nuevo_id]);
} else {
    // Si falló y habíamos subido un archivo nuevo, borrarlo
    if ($nuevo_logo) @unlink(DIR_BASE . $nuevo_logo);
    echo json_encode(['estado' => false, 'mensaje' => 'Error al guardar el cliente.']);
}
