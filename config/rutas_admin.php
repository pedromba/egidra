<?php
// Requiere que rutas_web.php ya haya sido cargado (RUTA_BASE y DIR_BASE definidos)

// ─── Rutas URL del módulo admin ───────────────────────────────────────────────
if (!defined('RUTA_ADMIN')) {
    // Si el admin es subdominio propio (admin.egidra.com), el DocRoot ya apunta
    // a la carpeta /admin/, por lo que RUTA_BASE = raíz del subdominio.
    // Detectamos esto comparando el DocRoot con el directorio físico del admin.
    $__docRoot  = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\'));
    $__adminDir = str_replace('\\', '/', rtrim(DIR_BASE . 'admin', '/\\'));
    $__isSubdomain = (rtrim($__docRoot, '/') === $__adminDir);
    unset($__docRoot, $__adminDir);

    if ($__isSubdomain) {
        define('RUTA_ADMIN',        RUTA_BASE);
        define('RUTA_ADMIN_SUPER',  RUTA_BASE . 'super/');
        define('RUTA_ADMIN_EDITOR', RUTA_BASE . 'editor/');
        define('RUTA_ADMIN_API',    RUTA_BASE . 'api/');
    } else {
        define('RUTA_ADMIN',        RUTA_BASE . 'admin/');
        define('RUTA_ADMIN_SUPER',  RUTA_BASE . 'admin/super/');
        define('RUTA_ADMIN_EDITOR', RUTA_BASE . 'admin/editor/');
        define('RUTA_ADMIN_API',    RUTA_BASE . 'admin/api/');
    }
    unset($__isSubdomain);
}

// ─── Rutas del sistema de archivos del admin ──────────────────────────────────
if (!defined('DIR_ADMIN')) {
    define('DIR_ADMIN',        DIR_BASE . 'admin/');
    define('DIR_ADMIN_SUPER',  DIR_BASE . 'admin/super/');
    define('DIR_ADMIN_EDITOR', DIR_BASE . 'admin/editor/');
}
