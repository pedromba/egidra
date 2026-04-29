<?php
// Requiere que rutas_web.php ya haya sido cargado (RUTA_BASE y DIR_BASE definidos)

// ─── Rutas URL del módulo admin ───────────────────────────────────────────────
if (!defined('RUTA_ADMIN')) {
    define('RUTA_ADMIN',        RUTA_BASE . 'admin/');
    define('RUTA_ADMIN_SUPER',  RUTA_BASE . 'admin/super/');
    define('RUTA_ADMIN_EDITOR', RUTA_BASE . 'admin/editor/');
    define('RUTA_ADMIN_API',    RUTA_BASE . 'admin/api/');
}

// ─── Rutas del sistema de archivos del admin ──────────────────────────────────
if (!defined('DIR_ADMIN')) {
    define('DIR_ADMIN',        DIR_BASE . 'admin/');
    define('DIR_ADMIN_SUPER',  DIR_BASE . 'admin/super/');
    define('DIR_ADMIN_EDITOR', DIR_BASE . 'admin/editor/');
}
