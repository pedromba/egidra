<?php
session_start();
session_unset();

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// admin/api/logout.php → sube 2 niveles para llegar a la raíz
require_once __DIR__ . '/../../config/init.php';

header('Location: ' . RUTA_ADMIN);
exit;
