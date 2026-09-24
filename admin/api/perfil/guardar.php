<?php
header('Content-Type: application/json');
ini_set('display_errors', '0');

session_start();
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['estado' => false, 'mensaje' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/logger.php';

$accion = $_POST['accion'] ?? '';
$id     = (int)$_SESSION['user_id'];

/* ── Actualizar info personal ── */
if ($accion === 'info') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email  = trim($_POST['email']  ?? '');

    if ($nombre === '' || $email === '') {
        echo json_encode(['estado' => false, 'mensaje' => 'Nombre y email son obligatorios.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['estado' => false, 'mensaje' => 'Email inválido.']);
        exit;
    }

    $stmt = $conexion->prepare(
        "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?"
    );
    $stmt->bind_param('ssi', $nombre, $email, $id);

    if (!$stmt->execute()) {
        $msg = str_contains($conexion->error, 'Duplicate') ? 'El email ya está en uso.' : 'Error al guardar.';
        echo json_encode(['estado' => false, 'mensaje' => $msg]);
        exit;
    }

    $_SESSION['nombre'] = $nombre;
    $_SESSION['email']  = $email;
    registrar_log($conexion, $id, 'EDITAR', 'Actualizó su perfil.', 'usuarios', $id);

    echo json_encode(['estado' => true, 'mensaje' => 'Información actualizada correctamente.']);
    exit;
}

/* ── Cambiar contraseña ── */
if ($accion === 'password') {
    $actual    = $_POST['actual']    ?? '';
    $nueva     = $_POST['nueva']     ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($actual === '' || $nueva === '' || $confirmar === '') {
        echo json_encode(['estado' => false, 'mensaje' => 'Completa todos los campos.']);
        exit;
    }
    if (strlen($nueva) < 8) {
        echo json_encode(['estado' => false, 'mensaje' => 'La nueva contraseña debe tener al menos 8 caracteres.']);
        exit;
    }
    if ($nueva !== $confirmar) {
        echo json_encode(['estado' => false, 'mensaje' => 'Las contraseñas nuevas no coinciden.']);
        exit;
    }

    $stmt = $conexion->prepare("SELECT contrasena_hash FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row || !password_verify($actual, $row['contrasena_hash'])) {
        echo json_encode(['estado' => false, 'mensaje' => 'La contraseña actual es incorrecta.']);
        exit;
    }

    $hash = password_hash($nueva, PASSWORD_DEFAULT);
    $stmt2 = $conexion->prepare("UPDATE usuarios SET contrasena_hash = ? WHERE id_usuario = ?");
    $stmt2->bind_param('si', $hash, $id);

    if (!$stmt2->execute()) {
        echo json_encode(['estado' => false, 'mensaje' => 'Error al actualizar la contraseña.']);
        exit;
    }

    registrar_log($conexion, $id, 'SISTEMA', 'Contraseña actualizada desde perfil.', 'usuarios', $id);
    echo json_encode(['estado' => true, 'mensaje' => 'Contraseña actualizada correctamente.']);
    exit;
}

echo json_encode(['estado' => false, 'mensaje' => 'Acción no reconocida.']);
