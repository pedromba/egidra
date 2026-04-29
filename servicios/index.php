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
    <title>Servicios - <?php echo $empNombre; ?></title>
    <meta name="description" content="Servicios industriales especializados de <?php echo $empNombre; ?>: Buceo &amp; Subsea, Acceso por Cuerda, Logística y Estudios Técnicos.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/servicios/servicios.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Lo Que Hacemos</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Nuestros <span class="text-warning">Servicios</span></h1>
                    <p class="lead text-white-50">Soluciones industriales especializadas para las operaciones más exigentes del sector Oil &amp; Gas.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#servicios" id="scrollIndicatorLink" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Nav dinámica de categorías (JS la inserta aquí) -->
    <div id="navPlaceholder"></div>

    <!-- Secciones de servicio (JS renderiza) -->
    <div id="serviciosContainer">
        <div class="py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-6"><div class="placeholder-glow"><div class="placeholder bg-secondary rounded-3 w-100" style="height:320px;"></div></div></div>
                    <div class="col-lg-6 d-flex flex-column gap-3 justify-content-center">
                        <span class="placeholder col-3"></span>
                        <span class="placeholder col-7 py-3"></span>
                        <span class="placeholder col-10"></span>
                        <span class="placeholder col-10"></span>
                        <span class="placeholder col-10"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificaciones (JS renderiza) -->
    <div id="certsContainer"></div>

    <!-- Proceso de trabajo — estático -->
    <section class="process-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Metodología</span>
                <h2 class="display-5 fw-bold mt-2 text-white">Nuestro Proceso de Trabajo</h2>
                <p class="text-white-50 mx-auto" style="max-width:580px;">Cada proyecto sigue un proceso estructurado que garantiza la seguridad, la calidad y el cumplimiento de plazos.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="process-step">
                        <div class="process-number">1</div>
                        <i class="fas fa-comments fa-2x text-warning mb-3"></i>
                        <h5 class="text-white fw-bold">Consulta</h5>
                        <p class="text-white-50 small">Análisis de necesidades y definición del alcance con el cliente.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="process-step">
                        <div class="process-number">2</div>
                        <i class="fas fa-file-alt fa-2x text-warning mb-3"></i>
                        <h5 class="text-white fw-bold">Planificación</h5>
                        <p class="text-white-50 small">Elaboración del plan de trabajo, evaluación de riesgos y asignación de recursos.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="process-step">
                        <div class="process-number">3</div>
                        <i class="fas fa-hard-hat fa-2x text-warning mb-3"></i>
                        <h5 class="text-white fw-bold">Ejecución</h5>
                        <p class="text-white-50 small">Despliegue del equipo técnico con supervisión HSE en tiempo real.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="process-step">
                        <div class="process-number">4</div>
                        <i class="fas fa-check-double fa-2x text-warning mb-3"></i>
                        <h5 class="text-white fw-bold">Entrega</h5>
                        <p class="text-white-50 small">Informe técnico final, dossier de calidad y cierre documental.</p>
                    </div>
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
                        <i class="fas fa-project-diagram mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="proyectos">0</h2>
                        <p class="text-muted mb-0">Proyectos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-building mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="clientes">0</h2>
                        <p class="text-muted mb-0">Clientes</p>
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
                        <i class="fas fa-layer-group mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="categorias">0</h2>
                        <p class="text-muted mb-0">Áreas de Servicio</p>
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
                                <span class="text-warning fw-bold text-uppercase">¿Necesita nuestros servicios?</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">Hablemos de su Proyecto</h2>
                                <p class="text-white-50 mb-4">Cuéntenos sobre sus necesidades operativas y nuestro equipo técnico le proporcionará una solución a medida.</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="../contacto/" class="btn btn-warning btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Solicitar Propuesta
                                    </a>
                                    <a href="../proyectos/" class="btn btn-outline-light btn-lg">
                                        <i class="fas fa-folder-open me-2"></i>Ver Proyectos
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-hard-hat fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">Disponibles 24/7</h3>
                                    <p class="text-dark mb-0">Respuesta en menos de 24 horas para cualquier requerimiento.</p>
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
    <script src="../recursos/js/servicios/main.js"></script>
</body>
</html>
