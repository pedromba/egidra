<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    session_unset();
    session_destroy();
    if (!defined('RUTA_BASE')) {
        require_once __DIR__ . '/../../../config/rutas.php';
    }
    header('Location: ' . RUTA_BASE . 'admin/');
    exit;
}
