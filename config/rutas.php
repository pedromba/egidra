<?php
// ─── Entorno ──────────────────────────────────────────────────────────────────
define('ENTORNO', isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost'
    ? 'local'
    : 'produccion'
);

// ─── Rutas URL ────────────────────────────────────────────────────────────────
define('RUTA_BASE',      ENTORNO === 'local'
    ? 'http://localhost/egidra.com/'
    : 'https://www.egidra.com/'
);

define('RUTA_RECURSOS',  RUTA_BASE . 'recursos/');
define('RUTA_CSS',       RUTA_RECURSOS . 'css/');
define('RUTA_JS',        RUTA_RECURSOS . 'js/');
define('RUTA_IMG',       RUTA_RECURSOS . 'img/');

// Páginas
define('URL_INICIO',         RUTA_BASE);
define('URL_SOBRE_NOSOTROS', RUTA_BASE . 'sobre-nosotros/');
define('URL_SERVICIOS',      RUTA_BASE . 'servicios/');
define('URL_SEGURIDAD',      RUTA_BASE . 'seguridad/');
define('URL_PROYECTOS',      RUTA_BASE . 'proyectos/');
define('URL_CONTACTO',       RUTA_BASE . 'contacto/');

// ─── Rutas del sistema de archivos ───────────────────────────────────────────
define('DIR_BASE',    dirname(__DIR__) . '/');
define('DIR_CONFIG',  DIR_BASE . 'config/');
define('DIR_INCLUDE', DIR_BASE . 'include/');
define('DIR_RECURSOS',DIR_BASE . 'recursos/');
