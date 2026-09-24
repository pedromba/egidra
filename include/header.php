<?php
if (!defined('RUTA_BASE')) require_once __DIR__ . '/../config/rutas_web.php';
if (!isset($conexion))    require_once __DIR__ . '/../config/conexion.php';

$_emp = $conexion->query("SELECT nombre, logo, logo_blanco FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

$_empNombre  = htmlspecialchars($_emp['nombre']  ?? 'EGIDRA');
$_logoBlanco = $_emp['logo_blanco'] ?? '';
$_logoPrinc  = $_emp['logo']        ?? '';
// Preferir versión blanca (fondo oscuro del navbar); si no, usar la principal
$_logoSrc = !empty($_logoBlanco) ? RUTA_BASE . ltrim($_logoBlanco, '/')
          : (!empty($_logoPrinc) ? RUTA_BASE . ltrim($_logoPrinc, '/') : '');

// ─── Sección activa del menú ───
// Primer segmento de la ruta relativa a la raíz del sitio ('' = inicio)
$_basePath = parse_url(RUTA_BASE, PHP_URL_PATH) ?: '/';
$_uriPath  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$_relPath  = str_starts_with($_uriPath, $_basePath) ? substr($_uriPath, strlen($_basePath)) : ltrim($_uriPath, '/');
$_seccion  = explode('/', $_relPath)[0];
if ($_seccion === 'index.php') $_seccion = '';

$_menu = [
    ''               => 'Inicio',
    'sobre-nosotros' => 'Sobre Nosotros',
    'servicios'      => 'Servicios',
    'seguridad'      => 'Seguridad HSE',
    'proyectos'      => 'Proyectos',
    'socios'         => 'Socios',
    'contacto'       => 'Contacto',
];
?>
<!-- Ajustes responsive comunes (después del CSS de cada página para prevalecer) -->
<link rel="stylesheet" href="<?php echo RUTA_CSS; ?>comun/responsive.css?v=<?php echo @filemtime(DIR_RECURSOS . 'css/comun/responsive.css'); ?>">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo RUTA_BASE; ?>">
            <?php if ($_logoSrc): ?>
                <img src="<?php echo htmlspecialchars($_logoSrc); ?>"
                     alt="<?php echo $_empNombre; ?>"
                     height="40"
                     class="me-2"
                     style="object-fit:contain;max-width:140px;">
            <?php else: ?>
                <i class="fas fa-anchor me-2"></i><strong><?php echo $_empNombre; ?></strong>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($_menu as $_slug => $_label): $_activo = ($_slug === $_seccion); ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo $_activo ? ' active' : ''; ?>"
                       href="<?php echo RUTA_BASE . ($_slug !== '' ? $_slug . '/' : ''); ?>"
                       <?php echo $_activo ? 'aria-current="page"' : ''; ?>><?php echo $_label; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- BASE_URL para JS -->
<script>const BASE_URL = '<?php echo RUTA_BASE; ?>';</script>
