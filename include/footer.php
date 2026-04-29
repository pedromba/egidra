<?php
if (!defined('RUTA_BASE'))  require_once __DIR__ . '/../config/rutas_web.php';
if (!isset($conexion))      require_once __DIR__ . '/../config/conexion.php';

$_emp = $conexion->query(
    "SELECT nombre, slogan, descripcion, email, telefono, direccion, ciudad, pais,
            logo, logo_blanco, linkedin, facebook, instagram
     FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc() ?? [];

$_cats = $conexion->query(
    "SELECT nombre, slug FROM categorias_servicios WHERE activo = 1 ORDER BY orden"
)->fetch_all(MYSQLI_ASSOC);

$_empNombre  = htmlspecialchars($_emp['nombre']      ?? 'EGIDRA');
$_empSlogan  = htmlspecialchars($_emp['slogan']       ?? '');
$_empEmail   = htmlspecialchars($_emp['email']        ?? '');
$_empTel     = htmlspecialchars($_emp['telefono']     ?? '');
$_empDir     = htmlspecialchars(trim(($_emp['ciudad'] ?? '') . ', ' . ($_emp['pais'] ?? '')), ENT_QUOTES);
$_empLI      = $_emp['linkedin']  ?? '';
$_empFB      = $_emp['facebook']  ?? '';
$_empIG      = $_emp['instagram'] ?? '';

// Logo para footer (preferir logo principal, si no logo blanco)
$_logoFooter = !empty($_emp['logo'])
    ? RUTA_BASE . ltrim($_emp['logo'], '/')
    : (!empty($_emp['logo_blanco']) ? RUTA_BASE . ltrim($_emp['logo_blanco'], '/') : '');
?>
<footer class="footer bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row g-4">

            <!-- Marca y descripción -->
            <div class="col-lg-4">
                <div class="mb-3">
                    <?php if ($_logoFooter): ?>
                        <img src="<?php echo htmlspecialchars($_logoFooter); ?>"
                             alt="<?php echo $_empNombre; ?>"
                             height="45"
                             style="object-fit:contain;max-width:160px;filter:brightness(0) invert(1);">
                    <?php else: ?>
                        <h4 class="fw-bold mb-0"><i class="fas fa-anchor me-2"></i><?php echo $_empNombre; ?></h4>
                    <?php endif; ?>
                </div>
                <?php if ($_empSlogan): ?>
                    <p class="text-warning small mb-2"><?php echo $_empSlogan; ?></p>
                <?php endif; ?>
                <p class="text-white-50 small">
                    <?php echo htmlspecialchars($_emp['descripcion'] ?? 'Empresa especializada en servicios industriales para el sector Oil & Gas.'); ?>
                </p>
                <div class="d-flex gap-3 mt-3">
                    <?php if ($_empLI): ?>
                        <a href="<?php echo htmlspecialchars($_empLI); ?>" class="text-white" target="_blank" rel="noopener"><i class="fab fa-linkedin fa-lg"></i></a>
                    <?php else: ?>
                        <span class="text-secondary"><i class="fab fa-linkedin fa-lg"></i></span>
                    <?php endif; ?>
                    <?php if ($_empFB): ?>
                        <a href="<?php echo htmlspecialchars($_empFB); ?>" class="text-white" target="_blank" rel="noopener"><i class="fab fa-facebook fa-lg"></i></a>
                    <?php else: ?>
                        <span class="text-secondary"><i class="fab fa-facebook fa-lg"></i></span>
                    <?php endif; ?>
                    <?php if ($_empIG): ?>
                        <a href="<?php echo htmlspecialchars($_empIG); ?>" class="text-white" target="_blank" rel="noopener"><i class="fab fa-instagram fa-lg"></i></a>
                    <?php else: ?>
                        <span class="text-secondary"><i class="fab fa-instagram fa-lg"></i></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navegación -->
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-warning mb-3">Navegación</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo URL_INICIO; ?>" class="text-white-50 text-decoration-none">Inicio</a></li>
                    <li class="mb-2"><a href="<?php echo URL_SOBRE_NOSOTROS; ?>" class="text-white-50 text-decoration-none">Sobre Nosotros</a></li>
                    <li class="mb-2"><a href="<?php echo URL_SERVICIOS; ?>" class="text-white-50 text-decoration-none">Servicios</a></li>
                    <li class="mb-2"><a href="<?php echo URL_SEGURIDAD; ?>" class="text-white-50 text-decoration-none">Seguridad HSE</a></li>
                    <li class="mb-2"><a href="<?php echo URL_PROYECTOS; ?>" class="text-white-50 text-decoration-none">Proyectos</a></li>
                    <li class="mb-2"><a href="<?php echo URL_SOCIOS; ?>" class="text-white-50 text-decoration-none">Socios</a></li>
                    <li class="mb-2"><a href="<?php echo URL_CONTACTO; ?>" class="text-white-50 text-decoration-none">Contacto</a></li>
                </ul>
            </div>

            <!-- Servicios dinámicos -->
            <div class="col-6 col-lg-2">
                <h6 class="fw-bold text-warning mb-3">Servicios</h6>
                <ul class="list-unstyled">
                    <?php foreach ($_cats as $_cat): ?>
                        <li class="mb-2">
                            <a href="<?php echo URL_SERVICIOS . '#' . htmlspecialchars($_cat['slug']); ?>"
                               class="text-white-50 text-decoration-none">
                                <?php echo htmlspecialchars($_cat['nombre']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($_cats)): ?>
                        <li class="mb-2"><a href="<?php echo URL_SERVICIOS; ?>" class="text-white-50 text-decoration-none">Ver servicios</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contacto dinámico -->
            <div class="col-lg-4">
                <h6 class="fw-bold text-warning mb-3">Contacto</h6>
                <ul class="list-unstyled">
                    <?php if ($_empTel): ?>
                        <li class="mb-2"><i class="fas fa-phone me-2 text-warning"></i><?php echo $_empTel; ?></li>
                    <?php endif; ?>
                    <?php if ($_empEmail): ?>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-warning"></i>
                            <a href="mailto:<?php echo $_empEmail; ?>" class="text-white-50 text-decoration-none"><?php echo $_empEmail; ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if (trim($_empDir, ', ')): ?>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i><?php echo $_empDir; ?></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <hr class="my-4 border-secondary">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-white-50 small mb-0">
                    &copy; <?php echo date('Y'); ?> <?php echo $_empNombre; ?>. Todos los derechos reservados.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-white-50 small text-decoration-none me-3">Política de Privacidad</a>
                <a href="#" class="text-white-50 small text-decoration-none">Términos de Servicio</a>
            </div>
        </div>

    </div>
</footer>
