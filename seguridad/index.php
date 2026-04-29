<?php
require_once '../config/rutas.php';
require_once '../config/conexion.php';

$empresa   = $conexion->query("SELECT nombre FROM empresa WHERE id = 1 LIMIT 1")->fetch_assoc();
$empNombre = htmlspecialchars($empresa['nombre'] ?? 'EGIDRA');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad HSE - <?php echo $empNombre; ?></title>
    <meta name="description" content="Política de Seguridad HSE de <?php echo $empNombre; ?>: Las 9 Reglas de Oro, certificaciones internacionales y compromiso con cero accidentes.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/seguridad/seguridad.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">HSE</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Seguridad <span class="text-warning">HSE</span></h1>
                    <p class="lead text-white-50">La seguridad no es una opción, es nuestra razón de ser. Cero accidentes es nuestro único objetivo en cada operación.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#politica" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Política HSE — estático (metodología de la empresa) -->
    <section id="politica" class="policy-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=620&h=460&fit=crop"
                         alt="Política HSE <?php echo $empNombre; ?>" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <span class="text-warning fw-bold text-uppercase">Nuestra Política</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Compromiso Total con la Seguridad</h2>
                    <p class="text-muted mb-4">En <?php echo $empNombre; ?>, la seguridad de nuestro equipo y de quienes nos rodean está por encima de cualquier objetivo operativo o comercial. Nuestro sistema de gestión HSE integra las mejores prácticas internacionales del sector Oil &amp; Gas.</p>
                    <p class="text-muted mb-4">Cada trabajador tiene la autoridad y la responsabilidad de detener cualquier operación que considere insegura, sin consecuencias negativas para él.</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="policy-pill"><i class="fas fa-shield-halved text-warning"></i>Stop Work Authority</span>
                        <span class="policy-pill"><i class="fas fa-eye text-warning"></i>Observación de Seguridad</span>
                        <span class="policy-pill"><i class="fas fa-clipboard-list text-warning"></i>Permiso de Trabajo</span>
                        <span class="policy-pill"><i class="fas fa-triangle-exclamation text-warning"></i>Análisis de Riesgos</span>
                        <span class="policy-pill"><i class="fas fa-users text-warning"></i>Toolbox Meeting</span>
                        <span class="policy-pill"><i class="fas fa-chart-line text-warning"></i>KPIs de Seguridad</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estadísticas HSE (JS renderiza desde estadisticas_hse) -->
    <section class="hse-stats-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Indicadores</span>
                <h2 class="display-5 fw-bold mt-2 text-white">Rendimiento en Seguridad</h2>
            </div>
            <div class="row g-4 justify-content-center" id="hseStatsContainer">
                <!-- Skeleton -->
                <div class="col-6 col-md-3"><div class="hse-stat-item placeholder-glow"><div class="hse-stat-icon mb-3"><span class="placeholder rounded-circle" style="width:40px;height:40px;display:block;"></span></div><span class="placeholder col-6 d-block mb-2"></span><span class="placeholder col-8"></span></div></div>
                <div class="col-6 col-md-3"><div class="hse-stat-item placeholder-glow"><div class="hse-stat-icon mb-3"><span class="placeholder rounded-circle" style="width:40px;height:40px;display:block;"></span></div><span class="placeholder col-6 d-block mb-2"></span><span class="placeholder col-8"></span></div></div>
                <div class="col-6 col-md-3"><div class="hse-stat-item placeholder-glow"><div class="hse-stat-icon mb-3"><span class="placeholder rounded-circle" style="width:40px;height:40px;display:block;"></span></div><span class="placeholder col-6 d-block mb-2"></span><span class="placeholder col-8"></span></div></div>
                <div class="col-6 col-md-3"><div class="hse-stat-item placeholder-glow"><div class="hse-stat-icon mb-3"><span class="placeholder rounded-circle" style="width:40px;height:40px;display:block;"></span></div><span class="placeholder col-6 d-block mb-2"></span><span class="placeholder col-8"></span></div></div>
            </div>
        </div>
    </section>

    <!-- 9 Reglas de Oro (JS renderiza desde reglas_oro) -->
    <section id="reglas" class="rules-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Normativa Interna</span>
                <h2 class="display-5 fw-bold mt-2" id="reglasTitulo">Las Reglas de Oro</h2>
                <p class="text-muted mx-auto" style="max-width:620px;">Son normas de cumplimiento obligatorio e innegociable. Su incumplimiento puede tener consecuencias disciplinarias inmediatas, incluyendo el cese de la actividad.</p>
            </div>
            <div class="row g-4" id="reglasContainer">
                <!-- Skeleton -->
                <?php for ($s = 0; $s < 9; $s++): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card rule-card shadow-sm h-100 placeholder-glow">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="placeholder rounded-circle" style="width:44px;height:44px;flex-shrink:0;"></span>
                                <span class="placeholder rounded-circle" style="width:54px;height:54px;flex-shrink:0;"></span>
                                <span class="placeholder col-6"></span>
                            </div>
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-10"></span>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Certificaciones (JS renderiza desde certificaciones) -->
    <section id="certificaciones" class="certifications-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Acreditaciones</span>
                <h2 class="display-5 fw-bold mt-2">Certificaciones Internacionales</h2>
                <p class="text-muted mx-auto" style="max-width:580px;">Nuestro sistema de gestión HSE está auditado y certificado por organismos internacionales independientes.</p>
            </div>
            <div class="row g-4 justify-content-center" id="certsContainer">
                <!-- Skeleton -->
                <?php for ($s = 0; $s < 6; $s++): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card cert-card shadow-sm text-center p-4 h-100 placeholder-glow">
                        <span class="placeholder rounded-circle mx-auto mb-3" style="width:68px;height:68px;display:block;"></span>
                        <span class="placeholder col-8 d-block mb-2 mx-auto"></span>
                        <span class="placeholder col-10 mx-auto"></span>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Stats (JS carga y anima) -->
    <section class="stats-section py-4">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-project-diagram mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="proyectos">0</h2>
                        <p class="text-muted mb-0">Proyectos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-shield-halved mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="accidentes">0</h2>
                        <p class="text-muted mb-0">Accidentes</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-clock mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="anios">0</h2>
                        <p class="text-muted mb-0">Años</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-certificate mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="certs">0</h2>
                        <p class="text-muted mb-0">Certificaciones</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA — estático -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-dark border-0 rounded-3 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-7 p-5">
                                <span class="text-warning fw-bold text-uppercase">¿Trabaja con nosotros?</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">Consulte Nuestra Documentación HSE</h2>
                                <p class="text-white-50 mb-4">Si es cliente, contratista o desea conocer en detalle nuestros procedimientos de seguridad, póngase en contacto con nuestro departamento HSE.</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="../contacto/" class="btn btn-warning btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Contactar Dpto. HSE
                                    </a>
                                    <a href="../servicios/" class="btn btn-outline-light btn-lg">
                                        <i class="fas fa-hard-hat me-2"></i>Ver Servicios
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-shield-halved fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">Safety First</h3>
                                    <p class="text-dark mb-0">La seguridad es el valor que nunca negociamos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "../include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos/js/seguridad/main.js"></script>
</body>
</html>
