<?php
// ─── Configuración central del proyecto ──────────────────────────────────────
// Este es el archivo maestro que init.php busca para detectar la raíz.

if (!defined('RUTA_BASE')) {
    require_once __DIR__ . '/rutas_web.php';
}

if (!defined('RUTA_ADMIN')) {
    require_once __DIR__ . '/rutas_admin.php';
}
