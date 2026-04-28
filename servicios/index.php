<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - EGIDRA</title>
    <meta name="description" content="Servicios industriales especializados de EGIDRA: Buceo & Subsea, Acceso por Cuerda, Logística y Estudios Técnicos para el sector Oil & Gas.">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/servicios/servicios.css">
</head>
<body>

    <!-- Navbar -->
    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Lo Que Hacemos</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Nuestros <span class="text-warning">Servicios</span></h1>
                    <p class="lead text-white-50">Soluciones industriales especializadas para las operaciones más exigentes del sector Oil & Gas.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#buceo" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Navegación rápida -->
    <nav class="services-nav shadow-sm">
        <div class="container">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#buceo">
                        <i class="fas fa-diving-mask"></i>Buceo &amp; Subsea
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cuerda">
                        <i class="fas fa-rope"></i>Acceso por Cuerda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#logistica">
                        <i class="fas fa-truck-loading"></i>Logística
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#estudios">
                        <i class="fas fa-clipboard-check"></i>Estudios Técnicos
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ── Servicio 1: Buceo & Subsea ─────────────────────────────── -->
    <section id="buceo" class="service-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1513828583688-c52646db42da?w=620&h=460&fit=crop"
                         alt="Buceo Industrial EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <div class="category-badge">
                        <i class="fas fa-diving-mask"></i> Buceo &amp; Subsea
                    </div>
                    <h2 class="display-5 fw-bold mb-3">Operaciones Subsea de Alta Precisión</h2>
                    <p class="text-muted mb-4">Ejecutamos operaciones de buceo industrial en las condiciones más exigentes del sector offshore. Nuestro equipo de buzos certificados garantiza seguridad, calidad y eficiencia en cada intervención submarina.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-search fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Inspección Submarina</h6>
                                        <p class="text-muted small mb-0">Inspección visual y técnica de estructuras, tuberías, ánodos y sistemas PLEM/PLET mediante buzos certificados y ROV.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-wrench fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Mantenimiento y Reparación</h6>
                                        <p class="text-muted small mb-0">Intervenciones correctivas y preventivas en infraestructura offshore: limpieza de mamparos, soldadura hiperbárica y reemplazos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-anchor fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Instalación Subsea</h6>
                                        <p class="text-muted small mb-0">Posicionamiento e instalación de estructuras submarinas, conexiones de riser y trabajos de fondeo en aguas profundas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>IMCA D 014</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>HSE Diving</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>ADCI</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Servicio 2: Acceso por Cuerda ──────────────────────────── -->
    <section id="cuerda" class="service-section bg-alt">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=620&h=460&fit=crop"
                         alt="Rope Access EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="category-badge">
                        <i class="fas fa-rope"></i> Acceso por Cuerda
                    </div>
                    <h2 class="display-5 fw-bold mb-3">Trabajo en Altura con Técnica IRATA</h2>
                    <p class="text-muted mb-4">Nuestros técnicos certificados IRATA realizan trabajos en superficies verticales y en altura extrema de forma segura y eficiente, eliminando la necesidad de andamiajes convencionales.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-binoculars fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Inspección en Altura</h6>
                                        <p class="text-muted small mb-0">Inspección y evaluación de estructuras metálicas, chimeneas industriales, tanques y torres de procesamiento.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-paint-roller fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Pintura y Revestimiento</h6>
                                        <p class="text-muted small mb-0">Aplicación de sistemas de protección anticorrosiva en superficies de difícil acceso mediante técnicas de rope access.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-tools fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Mantenimiento Estructural</h6>
                                        <p class="text-muted small mb-0">Reparaciones, refuerzos y trabajos de soldadura en altura en plataformas, flares y estructuras offshore.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>IRATA Nivel I-III</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>SPRAT</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Servicio 3: Logística ───────────────────────────────────── -->
    <section id="logistica" class="service-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=620&h=460&fit=crop"
                         alt="Logística Offshore EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <div class="category-badge">
                        <i class="fas fa-truck-loading"></i> Logística
                    </div>
                    <h2 class="display-5 fw-bold mb-3">Gestión Integral de Operaciones</h2>
                    <p class="text-muted mb-4">Coordinamos y ejecutamos la logística completa para operaciones offshore y onshore: desde el transporte de personal y equipos hasta el suministro continuo de plataformas en alta mar.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-ship fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Suministro Offshore</h6>
                                        <p class="text-muted small mb-0">Gestión de supply vessels y coordinación de cargas para plataformas en alta mar con seguimiento en tiempo real.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-boxes fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Almacenamiento y Distribución</h6>
                                        <p class="text-muted small mb-0">Gestión de almacenes técnicos, control de inventario de equipos especializados y distribución a puntos de operación.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-helicopter fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Movilización de Personal</h6>
                                        <p class="text-muted small mb-0">Coordinación de traslados terrestres, marítimos y aéreos (helicóptero) hacia instalaciones offshore en cumplimiento con las normas POB.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>ISO 9001</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>HUET Certified</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Servicio 4: Estudios Técnicos ───────────────────────────── -->
    <section id="estudios" class="service-section bg-alt">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=620&h=460&fit=crop"
                         alt="Estudios Técnicos EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="category-badge">
                        <i class="fas fa-clipboard-check"></i> Estudios Técnicos
                    </div>
                    <h2 class="display-5 fw-bold mb-3">Ingeniería y Análisis Especializado</h2>
                    <p class="text-muted mb-4">Nuestro equipo de ingenieros desarrolla estudios técnicos rigurosos que respaldan decisiones críticas de operación, mantenimiento e integridad en instalaciones del sector Oil & Gas.</p>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-wave-square fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Análisis de Integridad</h6>
                                        <p class="text-muted small mb-0">Evaluación del estado de estructuras offshore mediante END (Ensayos No Destructivos), análisis de corrosión y fatiga.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-drafting-compass fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Ingeniería de Proyecto</h6>
                                        <p class="text-muted small mb-0">Elaboración de procedimientos técnicos, planos as-built, especificaciones de materiales y gestión documental para proyectos offshore.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card sub-service-card shadow-sm">
                                <div class="card-body d-flex align-items-start gap-3 p-3">
                                    <div class="sub-service-icon">
                                        <i class="fas fa-chart-line fa-lg text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Estudios de Riesgo</h6>
                                        <p class="text-muted small mb-0">HAZOP, análisis RAM y evaluaciones ALARP para garantizar la operación segura de instalaciones industriales.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>DNV GL</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>ISO 14001</span>
                        <span class="badge bg-dark fs-6 py-2 px-3"><i class="fas fa-certificate me-1 text-warning"></i>API RP 2A</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Proceso de trabajo ──────────────────────────────────────── -->
    <section class="process-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Metodología</span>
                <h2 class="display-5 fw-bold mt-2 text-white">Nuestro Proceso de Trabajo</h2>
                <p class="text-white-50 mx-auto" style="max-width: 580px;">Cada proyecto sigue un proceso estructurado que garantiza la seguridad, la calidad y el cumplimiento de plazos.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="process-step">
                        <div class="process-number">1</div>
                        <i class="fas fa-comments fa-2x text-warning mb-3"></i>
                        <h5 class="text-white fw-bold">Consulta</h5>
                        <p class="text-white-50 small">Análisis de necesidades y definición del alcance del proyecto con el cliente.</p>
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
                        <p class="text-white-50 small">Informe técnico final, dossier de calidad y cierre documental del proyecto.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Stats ──────────────────────────────────────────────────── -->
    <section class="stats-section py-4">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-project-diagram mb-2"></i>
                        <h2 class="stat-number" data-target="500">0</h2>
                        <p class="text-muted mb-0">Proyectos</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-building mb-2"></i>
                        <h2 class="stat-number" data-target="50">0</h2>
                        <p class="text-muted mb-0">Clientes</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-clock mb-2"></i>
                        <h2 class="stat-number" data-target="20">0</h2>
                        <p class="text-muted mb-0">Años</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-users mb-2"></i>
                        <h2 class="stat-number" data-target="200">0</h2>
                        <p class="text-muted mb-0">Profesionales</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CTA ────────────────────────────────────────────────────── -->
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
                                    <p class="text-dark mb-0">Respuesta en menos de 24 horas para cualquier requerimiento operativo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "../include/footer.php"; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos/js/inicio/main.js"></script>

    <script>
    // Resaltar enlace activo en la nav de servicios al hacer scroll
    (function () {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.services-nav .nav-link');

        function onScroll() {
            let current = '';
            sections.forEach(s => {
                if (window.scrollY >= s.offsetTop - 160) current = s.id;
            });
            navLinks.forEach(a => {
                a.classList.toggle('active', a.getAttribute('href') === '#' + current);
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    })();
    </script>
</body>
</html>
