<?php
if (!defined('RUTA_BASE')) require_once __DIR__ . '/../config/rutas_web.php';
if (!isset($conexion))    require_once __DIR__ . '/../config/conexion.php';

$_emp = $conexion->query("SELECT nombre, logo, logo_blanco FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

$_empNombre  = htmlspecialchars($_emp['nombre']  ?? 'EGIDRA');
$_logoBlanco = $_emp['logo_blanco'] ?? '';
$_logoPrinc  = $_emp['logo']        ?? '';
// Preferir versión blanca (fondo oscuro del navbar); si no, usar la principal
$_logoSrc = !empty($_logoBlanco) ? RUTA_BASE . $_logoBlanco
          : (!empty($_logoPrinc) ? RUTA_BASE . $_logoPrinc : '');
?>
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
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>sobre-nosotros/">Sobre Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>servicios/">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>seguridad/">Seguridad HSE</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>proyectos/">Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>socios/">Socios</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo RUTA_BASE; ?>contacto/">Contacto</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- BASE_URL para JS -->
<script>const BASE_URL = '<?php echo RUTA_BASE; ?>';</script>
