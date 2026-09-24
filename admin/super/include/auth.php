<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('RUTA_ADMIN')) {
    require_once __DIR__ . '/../../../config/config.php';
}

if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Super') {
    session_unset();
    session_destroy();
    header('Location: ' . RUTA_ADMIN);
    exit;
}

// Timeout de inactividad: 2 horas
if (isset($_SESSION['_last_activity']) && (time() - $_SESSION['_last_activity']) > 7200) {
    session_unset();
    session_destroy();
    header('Location: ' . RUTA_ADMIN . '?exp=1');
    exit;
}
$_SESSION['_last_activity'] = time();
