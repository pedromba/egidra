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
    <title>Socios y Alianzas - <?php echo $empNombre; ?></title>
    <meta name="description" content="<?php echo $empNombre; ?> trabaja con los organismos internacionales más reconocidos del sector industrial y offshore, garantizando los más altos estándares de calidad y seguridad.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/socios/socios.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header socios-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Alianzas Estratégicas</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Nuestros <span class="text-warning">Socios</span></h1>
                    <p class="lead text-white-50">Respaldados por los organismos internacionales más exigentes del sector industrial y offshore.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#socios" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Intro — Por qué estas alianzas -->
    <section class="partners-intro-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&h=450&fit=crop"
                         alt="Alianzas <?php echo $empNombre; ?>" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <span class="text-warning fw-bold text-uppercase">Filosofía de Alianzas</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Asociaciones que garantizan la excelencia</h2>
                    <p class="text-muted mb-4">En <?php echo $empNombre; ?> seleccionamos nuestros socios con el mismo rigor con el que ejecutamos cada proyecto. Cada alianza está orientada a ofrecer a nuestros clientes una garantía adicional de calidad, seguridad y cumplimiento normativo internacional.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-warning me-3 mt-1 flex-shrink-0"></i>
                            <span>Miembros activos de los principales organismos de la industria subsea y rope access</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-warning me-3 mt-1 flex-shrink-0"></i>
                            <span>Certificaciones internacionales auditadas anualmente por terceros independientes</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-check-circle text-warning me-3 mt-1 flex-shrink-0"></i>
                            <span>Acceso a las últimas normativas, guías técnicas y mejores prácticas del sector</span>
                        </li>
                    </ul>
                    <a href="../seguridad/" class="btn btn-dark">
                        <i class="fas fa-shield-halved me-2"></i>Ver Seguridad HSE
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Socios Grid (JS renderiza) -->
    <section id="socios" class="socios-grid-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Socios</span>
                <h2 class="display-5 fw-bold mt-2">Organismos que nos avalan</h2>
                <p class="text-muted mx-auto" style="max-width:620px;">Cada socio representa un compromiso adicional con la calidad, la seguridad y el cumplimiento normativo en cada operación que ejecutamos.</p>
            </div>
            <div class="row g-4" id="sociosGrid">
                <!-- Skeleton -->
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="col-md-6">
                    <div class="socio-card h-100 placeholder-glow">
                        <div class="socio-card-head">
                            <span class="placeholder rounded-3 flex-shrink-0" style="width:58px;height:58px;"></span>
                            <div class="flex-grow-1">
                                <span class="placeholder col-5 d-block mb-2" style="height:10px;"></span>
                                <span class="placeholder col-8 d-block" style="height:16px;"></span>
                            </div>
                        </div>
                        <span class="placeholder col-12 d-block mb-1" style="height:12px;"></span>
                        <span class="placeholder col-10 d-block mb-1" style="height:12px;"></span>
                        <span class="placeholder col-8 d-block" style="height:12px;"></span>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Beneficios para el cliente -->
    <section class="benefits-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Para Nuestros Clientes</span>
                <h2 class="display-5 fw-bold mt-2">¿Qué significa trabajar con <?php echo $empNombre; ?>?</h2>
                <p class="text-muted mx-auto" style="max-width:600px;">Nuestras alianzas se traducen en beneficios concretos y verificables para cada cliente y proyecto.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100">
                        <div class="benefit-icon mx-auto mb-3">
                            <i class="fas fa-shield-halved fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Seguridad Certificada</h5>
                        <p class="text-muted small mb-0">Operaciones auditadas bajo normas ISO 45001 e IRATA, con registros verificables de cero incidentes graves.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100">
                        <div class="benefit-icon mx-auto mb-3">
                            <i class="fas fa-medal fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Calidad Auditada</h5>
                        <p class="text-muted small mb-0">Sistema de gestión ISO 9001 verificado anualmente por Bureau Veritas, garantizando entregas conforme a especificación.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100">
                        <div class="benefit-icon mx-auto mb-3">
                            <i class="fas fa-globe fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Estándares Globales</h5>
                        <p class="text-muted small mb-0">Procedimientos alineados con las mejores prácticas internacionales reconocidas por las principales operadoras del sector Oil &amp; Gas.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="benefit-card h-100">
                        <div class="benefit-icon mx-auto mb-3">
                            <i class="fas fa-file-contract fa-2x text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Documentación Total</h5>
                        <p class="text-muted small mb-0">Trazabilidad completa de cada operación con informes, certificados y registros técnicos disponibles para auditorías de cliente.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certificaciones Vigentes (JS renderiza) -->
    <section class="certs-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop"
                         alt="Certificaciones <?php echo $empNombre; ?>" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="text-warning fw-bold text-uppercase">Certificaciones</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Acreditaciones Vigentes</h2>
                    <p class="text-muted mb-4">Todas nuestras certificaciones son auditadas periódicamente por organismos independientes, garantizando su plena validez en cada proyecto.</p>
                    <div class="row g-3 mb-4" id="certsContainer">
                        <!-- Skeleton -->
                        <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm placeholder-glow">
                                <span class="placeholder rounded-circle me-3 flex-shrink-0" style="width:28px;height:28px;"></span>
                                <div class="flex-grow-1">
                                    <span class="placeholder col-8 d-block mb-1" style="height:11px;"></span>
                                    <span class="placeholder col-6 d-block" style="height:9px;"></span>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <a href="../seguridad/" class="btn btn-dark">
                        <i class="fas fa-shield-halved me-2"></i>Ver Política HSE
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats (JS carga y anima) -->
    <section class="stats-section py-4">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-handshake mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="socios">0</h2>
                        <p class="text-muted mb-0">Socios Estratégicos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-certificate mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="certs">0</h2>
                        <p class="text-muted mb-0">Certificaciones</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-clock mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="anios">0</h2>
                        <p class="text-muted mb-0">Años de Experiencia</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-project-diagram mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="proyectos">0</h2>
                        <p class="text-muted mb-0">Proyectos Completados</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-dark border-0 rounded-3 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-7 p-5">
                                <span class="text-warning fw-bold text-uppercase">¿Necesita garantías adicionales?</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">Hablemos de su proyecto</h2>
                                <p class="text-white-50 mb-4">Nuestro equipo técnico puede proporcionar toda la documentación de certificaciones y membresías necesaria para su proceso de homologación de proveedores.</p>
                                <a href="../contacto/" class="btn btn-warning btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Contactar
                                </a>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-handshake fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">Socios de Confianza</h3>
                                    <p class="text-dark mb-0">Avales reconocidos a nivel mundial.</p>
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
    <script src="../recursos/js/socios/main.js"></script>
</body>
</html>
