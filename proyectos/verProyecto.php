<?php
require_once '../config/rutas.php';
require_once '../config/conexion.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ' . RUTA_BASE . 'proyectos/');
    exit;
}

$stmt = $conexion->prepare(
    "SELECT p.id_proyecto, p.titulo, p.descripcion_tecnica, p.ubicacion, p.ano_finalizacion, p.imagen,
            cs.nombre AS categoria, cs.icono AS categoria_icono,
            cl.nombre AS cliente
     FROM proyectos p
     LEFT JOIN categorias_servicios cs ON p.categoria_id = cs.id
     LEFT JOIN clientes cl            ON p.cliente_id   = cl.id_cliente
     WHERE p.id_proyecto = ? AND p.activo = 1
     LIMIT 1"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) {
    header('Location: ' . RUTA_BASE . 'proyectos/');
    exit;
}

$empresa   = $conexion->query("SELECT nombre FROM empresa WHERE id = 1 LIMIT 1")->fetch_assoc();
$empNombre = htmlspecialchars($empresa['nombre'] ?? 'EGIDRA');

$titulo    = htmlspecialchars($p['titulo']);
$desc      = htmlspecialchars($p['descripcion_tecnica'] ?? '');
$ubicacion = htmlspecialchars($p['ubicacion'] ?? '');
$anio      = htmlspecialchars($p['ano_finalizacion'] ?? '');
$categoria = htmlspecialchars($p['categoria'] ?? '');
$icono     = htmlspecialchars($p['categoria_icono'] ?? 'fa-anchor');
$cliente   = htmlspecialchars($p['cliente'] ?? '');
$imagenUrl = !empty($p['imagen']) ? RUTA_BASE . htmlspecialchars($p['imagen']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> — <?php echo $empNombre; ?></title>
    <meta name="description" content="<?php echo $desc; ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/proyectos/proyectos.css">
    <link rel="stylesheet" href="../recursos/css/proyectos/verProyecto.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Hero del proyecto -->
    <section class="vp-hero">
        <?php if ($imagenUrl): ?>
            <div class="vp-hero-img" style="background-image:url('<?php echo $imagenUrl; ?>')"></div>
        <?php else: ?>
            <div class="vp-hero-img vp-hero-placeholder">
                <i class="fas <?php echo $icono; ?> vp-placeholder-icon"></i>
            </div>
        <?php endif; ?>
        <div class="vp-hero-overlay"></div>
        <div class="container vp-hero-content">
            <a href="<?php echo RUTA_BASE; ?>proyectos/" class="vp-back-link">
                <i class="fas fa-arrow-left me-2"></i>Volver a proyectos
            </a>
            <?php if ($categoria): ?>
                <span class="badge bg-warning text-dark mb-3 vp-badge">
                    <i class="fas <?php echo $icono; ?> me-1"></i><?php echo $categoria; ?>
                </span>
            <?php endif; ?>
            <h1 class="vp-title"><?php echo $titulo; ?></h1>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="vp-main">
        <div class="container">
            <div class="row g-5">

                <!-- Descripción -->
                <div class="col-lg-8">
                    <div class="vp-section">
                        <h2 class="vp-section-title">
                            <i class="fas fa-file-alt me-2 text-warning"></i>Descripción técnica
                        </h2>
                        <p class="vp-desc"><?php echo nl2br($desc); ?></p>
                    </div>
                </div>

                <!-- Ficha técnica -->
                <div class="col-lg-4">
                    <div class="vp-ficha">
                        <h3 class="vp-ficha-title">
                            <i class="fas fa-clipboard-list me-2"></i>Ficha del proyecto
                        </h3>
                        <ul class="vp-ficha-list">
                            <?php if ($cliente): ?>
                            <li>
                                <span class="vp-ficha-label"><i class="fas fa-building me-2 text-warning"></i>Cliente</span>
                                <span class="vp-ficha-value"><?php echo $cliente; ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if ($ubicacion): ?>
                            <li>
                                <span class="vp-ficha-label"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Ubicación</span>
                                <span class="vp-ficha-value"><?php echo $ubicacion; ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if ($anio): ?>
                            <li>
                                <span class="vp-ficha-label"><i class="fas fa-calendar me-2 text-warning"></i>Año</span>
                                <span class="vp-ficha-value"><?php echo $anio; ?></span>
                            </li>
                            <?php endif; ?>
                            <?php if ($categoria): ?>
                            <li>
                                <span class="vp-ficha-label"><i class="fas <?php echo $icono; ?> me-2 text-warning"></i>Especialidad</span>
                                <span class="vp-ficha-value"><?php echo $categoria; ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <a href="<?php echo RUTA_BASE; ?>contacto/" class="btn btn-warning w-100 fw-bold mt-4">
                            <i class="fas fa-paper-plane me-2"></i>Solicitar propuesta similar
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Proyectos relacionados -->
    <section class="vp-related">
        <div class="container">
            <h2 class="vp-related-title">Otros proyectos</h2>
            <div class="row g-4" id="related-grid">
                <div class="col-12 text-center py-3">
                    <i class="fas fa-spinner fa-spin text-warning fa-2x"></i>
                </div>
            </div>
        </div>
    </section>

    <?php include "../include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>var CURRENT_ID = <?php echo $id; ?>;</script>
    <script src="../recursos/js/proyectos/verProyecto.js"></script>
</body>
</html>
