<?php
// ─── Entorno ──────────────────────────────────────────────────────────────────
if (!defined('ENTORNO')) {
    define('ENTORNO', isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost'
        ? 'local'
        : 'produccion'
    );
}

// ─── Rutas URL del sitio web (auto-detectadas, independientes del nombre de carpeta) ───
if (!defined('RUTA_BASE')) {
    $__protocol   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $__host       = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $__docRoot    = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\'));
    $__projectDir = str_replace('\\', '/', rtrim(dirname(__DIR__), '/\\'));
    // Si el proyecto está dentro del docRoot: ruta relativa normal.
    // Si el docRoot está dentro del proyecto (caso subdominio propio), usar '/'.
    if (str_starts_with($__projectDir, $__docRoot)) {
        $__webPath = rtrim(substr($__projectDir, strlen($__docRoot)), '/') . '/';
    } else {
        $__webPath = '/';
    }
    define('RUTA_BASE', "{$__protocol}://{$__host}{$__webPath}");
    unset($__protocol, $__host, $__docRoot, $__projectDir, $__webPath);
}

if (!defined('RUTA_RECURSOS')) {
    define('RUTA_RECURSOS', RUTA_BASE . 'recursos/');
    define('RUTA_CSS',      RUTA_RECURSOS . 'css/');
    define('RUTA_JS',       RUTA_RECURSOS . 'js/');
    define('RUTA_IMG',      RUTA_BASE . 'img/');
}

// Páginas del sitio web
if (!defined('URL_INICIO')) {
    define('URL_INICIO',         RUTA_BASE);
    define('URL_SOBRE_NOSOTROS', RUTA_BASE . 'sobre-nosotros/');
    define('URL_SERVICIOS',      RUTA_BASE . 'servicios/');
    define('URL_SEGURIDAD',      RUTA_BASE . 'seguridad/');
    define('URL_PROYECTOS',      RUTA_BASE . 'proyectos/');
    define('URL_SOCIOS',         RUTA_BASE . 'socios/');
    define('URL_CONTACTO',       RUTA_BASE . 'contacto/');
}

// ─── Rutas del sistema de archivos ───────────────────────────────────────────
if (!defined('DIR_BASE')) {
    define('DIR_BASE',     dirname(__DIR__) . '/');
    define('DIR_CONFIG',   DIR_BASE . 'config/');
    define('DIR_INCLUDE',  DIR_BASE . 'include/');
    define('DIR_RECURSOS', DIR_BASE . 'recursos/');
    define('DIR_IMG',      DIR_BASE . 'img/');
}
