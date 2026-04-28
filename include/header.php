<?php
if (!defined('RUTA_BASE')) {
    require_once __DIR__ . '/../config/rutas.php';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo RUTA_BASE; ?>">
                <i class="fas fa-anchor me-2"></i><strong>EGIDRA</strong>
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
