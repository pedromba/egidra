<?php
// ─── Detectar raíz del proyecto ────────────────────────────────────────────
// init.php está en config/, así que la raíz es su carpeta padre.
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__DIR__) . '/');
}

// ─── Cargar rutas (web + admin) ────────────────────────────────────────────
if (!defined('RUTA_BASE')) {
    require_once __DIR__ . '/config.php';
}

// ─── Cargar conexión a BD ─────────────────────────────────────────────────
if (!isset($conexion)) {
    require_once __DIR__ . '/conexion.php';
}
