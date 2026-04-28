<?php
if (!defined('RUTA_BASE')) require_once __DIR__ . '/../config/rutas.php';
?>

<footer class="footer bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-anchor me-2"></i>EGIDRA</h4>
                <p class="text-white-50">Empresa líder en servicios industriales especializados para el sector Oil & Gas. Más de 20 años de experiencia garantizando calidad y seguridad.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-warning mb-3">Navegación</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>" class="text-white-50 text-decoration-none">Inicio</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>sobre-nosotros/" class="text-white-50 text-decoration-none">Sobre Nosotros</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>servicios/" class="text-white-50 text-decoration-none">Servicios</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>seguridad/" class="text-white-50 text-decoration-none">Seguridad HSE</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>proyectos/" class="text-white-50 text-decoration-none">Proyectos</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>socios/" class="text-white-50 text-decoration-none">Socios</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>contacto/" class="text-white-50 text-decoration-none">Contacto</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-warning mb-3">Servicios</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>servicios/" class="text-white-50 text-decoration-none">Buceo &amp; Subsea</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>servicios/" class="text-white-50 text-decoration-none">Acceso por Cuerda</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>servicios/" class="text-white-50 text-decoration-none">Logística</a></li>
                    <li class="mb-2"><a href="<?php echo RUTA_BASE; ?>servicios/" class="text-white-50 text-decoration-none">Estudios Técnicos</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold text-warning mb-3">Contacto</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-phone me-2 text-warning"></i>+240 222 XXX XXX</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2 text-warning"></i>info@egidra.com</li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Malabo, Guinea Ecuatorial</li>
                </ul>
            </div>
        </div>
        <hr class="my-4 border-secondary">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-white-50 small mb-0">&copy; 2026 EGIDRA. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-white-50 small text-decoration-none me-3">Política de Privacidad</a>
                <a href="#" class="text-white-50 small text-decoration-none">Términos de Servicio</a>
            </div>
        </div>
    </div>
</footer>