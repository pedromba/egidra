<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos - EGIDRA</title>
    <meta name="description" content="Track record de EGIDRA: proyectos ejecutados para Marathon, Chevron, Trident, Repsol, Cepsa y BP en buceo industrial, rope access, logística y estudios técnicos.">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/proyectos/proyectos.css">
</head>
<body>

    <!-- Navbar -->
    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Track Record</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Nuestros <span class="text-warning">Proyectos</span></h1>
                    <p class="lead text-white-50">Más de 500 proyectos ejecutados con éxito para las principales compañías del sector Oil & Gas a nivel mundial.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#proyectos" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- ── Filtros ────────────────────────────────────────────────── -->
    <section class="filter-section shadow-sm">
        <div class="container">
            <div class="d-flex gap-2 flex-wrap justify-content-center">
                <button class="filter-btn active" data-filter="todos">
                    <i class="fas fa-th-large me-1"></i>Todos
                </button>
                <button class="filter-btn" data-filter="buceo">
                    <i class="fas fa-diving-mask me-1"></i>Buceo &amp; Subsea
                </button>
                <button class="filter-btn" data-filter="cuerda">
                    <i class="fas fa-rope me-1"></i>Acceso por Cuerda
                </button>
                <button class="filter-btn" data-filter="logistica">
                    <i class="fas fa-truck-loading me-1"></i>Logística
                </button>
                <button class="filter-btn" data-filter="estudios">
                    <i class="fas fa-clipboard-check me-1"></i>Estudios Técnicos
                </button>
            </div>
        </div>
    </section>

    <!-- ── Grid de proyectos ──────────────────────────────────────── -->
    <section id="proyectos" class="projects-section">
        <div class="container">
            <div class="row g-4" id="projects-grid">

                <!-- Proyecto 1 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="buceo">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&h=400&fit=crop" alt="Inspección PLEM">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Buceo Industrial</span>
                            <h5 class="fw-bold mb-2">Inspección Sistema PLEM</h5>
                            <p class="text-muted small mb-0">Inspección visual y de espesores en sistema PLEM offshore, incluyendo inspección de ánodos y estructuras de soporte mediante buzos certificados.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Pta. Europa</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Marathon</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2023</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 2 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="cuerda">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&h=400&fit=crop" alt="Mantenimiento Plataforma">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Rope Access</span>
                            <h5 class="fw-bold mb-2">Mantenimiento Estructura Plataforma</h5>
                            <p class="text-muted small mb-0">Trabajos de inspección, pintura anticorrosiva y reparación de juntas en superestructura de plataforma offshore mediante técnicas IRATA nivel III.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Región Insular</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Chevron</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2023</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 3 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="logistica">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=600&h=400&fit=crop" alt="Suministro Offshore">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Logística</span>
                            <h5 class="fw-bold mb-2">Suministro Integral Offshore</h5>
                            <p class="text-muted small mb-0">Gestión logística integral para abastecimiento de plataforma, incluyendo coordinación de supply vessel, gestión de cargas peligrosas y control POB.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Múltiples Regiones</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Trident</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2022</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 4 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="buceo">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&h=400&fit=crop" alt="Instalación Riser">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Buceo Industrial</span>
                            <h5 class="fw-bold mb-2">Instalación Riser Submarino</h5>
                            <p class="text-muted small mb-0">Posicionamiento e instalación de riser flexible en zona de splash zone y conexión con estructura de fondo marino a 40 metros de profundidad.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Costa Atlántica</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Repsol</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2022</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 5 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="estudios">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop" alt="Análisis Integridad">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Estudios Técnicos</span>
                            <h5 class="fw-bold mb-2">Análisis de Integridad Estructural</h5>
                            <p class="text-muted small mb-0">Evaluación de integridad mediante END (ultrasonidos y partículas magnéticas) en estructura jacket de plataforma fija con elaboración de informe RAM.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Mar del Norte</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>BP</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2023</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 6 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="cuerda">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop" alt="Pintura Chimenea">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Rope Access</span>
                            <h5 class="fw-bold mb-2">Pintura Anticorrosiva Chimenea</h5>
                            <p class="text-muted small mb-0">Preparación de superficie (Sa 2½) y aplicación de sistema tricapa de pintura anticorrosiva en chimenea industrial de 60 m de altura.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Refinería Sur</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Cepsa</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2022</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 7 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="buceo">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&h=400&fit=crop" alt="Limpieza Ánodos">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Buceo Industrial</span>
                            <h5 class="fw-bold mb-2">Reemplazo de Ánodos de Sacrificio</h5>
                            <p class="text-muted small mb-0">Sustitución de ánodos de sacrificio en monoboya de carga y estructura de fondo, incluyendo limpieza de incrustaciones biológicas y registro fotográfico.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Terminal Cargadero</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Marathon</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2021</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 8 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="logistica">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&h=400&fit=crop" alt="Movilización">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Logística</span>
                            <h5 class="fw-bold mb-2">Movilización de Equipo de Campaña</h5>
                            <p class="text-muted small mb-0">Planificación y ejecución de movilización completa de equipo de buceo (DSV, campana, sistemas de vida) para campaña de 3 meses en aguas ecuatorianas.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Guinea Ecuatorial</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Trident</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2021</span>
                        </div>
                    </div>
                </div>

                <!-- Proyecto 9 -->
                <div class="col-md-6 col-lg-4 project-item" data-category="estudios">
                    <div class="card project-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop" alt="HAZOP">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Estudios Técnicos</span>
                            <h5 class="fw-bold mb-2">Estudio HAZOP Planta de Gas</h5>
                            <p class="text-muted small mb-0">Análisis de riesgos y operabilidad (HAZOP) de planta de tratamiento de gas natural, incluyendo propuestas de salvaguardas y actualización de P&IDs.</p>
                        </div>
                        <div class="card-footer">
                            <span><i class="fas fa-map-marker-alt me-1 text-warning"></i>Planta Onshore</span>
                            <span><i class="fas fa-building me-1 text-warning"></i>Repsol</span>
                            <span><i class="fas fa-calendar me-1 text-warning"></i>2024</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sin resultados -->
            <div id="no-results" class="text-center py-5 d-none">
                <i class="fas fa-folder-open fa-4x text-warning mb-3 d-block"></i>
                <h5 class="fw-bold">No hay proyectos en esta categoría</h5>
                <p class="text-muted">Prueba con otro filtro para ver más trabajos.</p>
            </div>
        </div>
    </section>

    <!-- ── Clientes ───────────────────────────────────────────────── -->
    <section class="clients-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Con Quiénes Trabajamos</span>
                <h2 class="display-5 fw-bold mt-2">Nuestros Clientes</h2>
                <p class="text-muted mx-auto" style="max-width: 560px;">Compañías líderes del sector Oil & Gas confían en EGIDRA para sus operaciones más críticas.</p>
            </div>
            <div class="row g-3 justify-content-center align-items-center">
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>MARATHON</span></div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>CHEVRON</span></div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>TRIDENT</span></div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>REPSOL</span></div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>CEPSA</span></div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="client-logo"><span>BP</span></div>
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
                        <i class="fas fa-globe mb-2"></i>
                        <h2 class="stat-number" data-target="12">0</h2>
                        <p class="text-muted mb-0">Países</p>
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
                                <span class="text-warning fw-bold text-uppercase">¿Su proyecto es el próximo?</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">Cuéntenos su Desafío</h2>
                                <p class="text-white-50 mb-4">Contamos con la experiencia y los medios para afrontar los proyectos más exigentes del sector. Solicite una propuesta sin compromiso.</p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="../contacto/" class="btn btn-warning btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Solicitar Propuesta
                                    </a>
                                    <a href="../servicios/" class="btn btn-outline-light btn-lg">
                                        <i class="fas fa-hard-hat me-2"></i>Ver Servicios
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-folder-open fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">+500 Proyectos</h3>
                                    <p class="text-dark mb-0">Cada proyecto ejecutado es un nuevo estándar de calidad.</p>
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
    (function () {
        const btns  = document.querySelectorAll('.filter-btn');
        const items = document.querySelectorAll('.project-item');
        const noRes = document.getElementById('no-results');

        btns.forEach(btn => {
            btn.addEventListener('click', function () {
                btns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                let visible = 0;

                items.forEach(item => {
                    const show = filter === 'todos' || item.dataset.category === filter;
                    item.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                noRes.classList.toggle('d-none', visible > 0);
            });
        });
    })();
    </script>
</body>
</html>
