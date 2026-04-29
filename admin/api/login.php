<?php
header('Content-Type: application/json');

// admin/api/login.php → sube 2 niveles para llegar a la raíz
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../config/logger.php';

$remember = !empty($_POST['recordarme']);

if ($remember) {
    $lifetime = 30 * 24 * 60 * 60;
    ini_set('session.gc_maxlifetime', $lifetime);
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
}

session_start();

$email    = trim($_POST['email'] ?? '');
$password = $_POST['contrasena'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['estado' => false, 'mensaje' => 'Por favor, complete todos los campos.']);
    exit;
}

$stmt = $conexion->prepare(
    "SELECT id_usuario, email, nombre, contrasena_hash, rol, estado
     FROM usuarios WHERE email = ? AND estado = 'activo'"
);
$stmt->bind_param('s', $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo json_encode(['estado' => false, 'mensaje' => 'Usuario no encontrado.']);
    exit;
}

$usuario = $resultado->fetch_assoc();

if (!password_verify($password, $usuario['contrasena_hash'])) {
    echo json_encode(['estado' => false, 'mensaje' => 'Contraseña incorrecta.']);
    exit;
}

$redirects = [
    'Super'  => RUTA_ADMIN_SUPER,
    'Editor' => RUTA_ADMIN_EDITOR,
];

if (!array_key_exists($usuario['rol'], $redirects)) {
    echo json_encode(['estado' => false, 'mensaje' => 'Rol no reconocido.']);
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = $usuario['id_usuario'];
$_SESSION['email']   = $usuario['email'];
$_SESSION['nombre']  = $usuario['nombre'];
$_SESSION['estado']  = $usuario['estado'];
$_SESSION['rol']     = $usuario['rol'];

registrar_log($conexion, $usuario['id_usuario'], 'LOGIN', 'Inicio de sesión: ' . $usuario['email'], 'usuarios', $usuario['id_usuario']);

echo json_encode(['estado' => true, 'redirect' => $redirects[$usuario['rol']]]);
