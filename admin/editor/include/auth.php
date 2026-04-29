<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id']) || $_SESSION['rol'] !== 'Editor') {
    session_unset();
    session_destroy();
    if (!defined('RUTA_ADMIN')) {
        require_once __DIR__ . '/../../../config/config.php';
    }
    header('Location: ' . RUTA_ADMIN);
    exit;
}
