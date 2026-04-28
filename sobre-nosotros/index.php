<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - EGIDRA</title>
    <meta name="description" content="Conoce más sobre EGIDRA, empresa líder en servicios industriales especializados para el sector Oil & Gas.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/sobre-nosotros/sobre-nosotros.css">
</head>
<body>

    <!-- Navbar -->
    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Quiénes Somos</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Sobre <span class="text-warning">EGIDRA</span></h1>
                    <p class="lead text-white-50">Más de 20 años de trayectoria en servicios industriales especializados para el sector Oil & Gas.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#historia" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Historia y Trayectoria -->
    <section id="historia" class="history-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&h=450&fit=crop" alt="Historia EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <span class="text-warning fw-bold text-uppercase">Nuestra Historia</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Trayectoria e Innovación</h2>
                    <p class="text-muted mb-4">EGIDRA nació con la visión de proporcionar servicios industriales de excellence para las operaciones más exigentes del sector Oil & Gas. Desde entonces, hemos crecido hasta convertirnos en un referente de calidad y seguridad.</p>
                    <p class="text-muted mb-4">Nuestra trayectoria incluye la ejecución de proyectos de alta complejidad en regiones offshore challenging, manteniendo siempre los más altos estándares de seguridad y calidad.</p>
                    <div class="row g-3 mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check fa-2x text-warning me-3"></i>
                                <div>
                                    <strong class="d-block">Fundada</strong>
                                    <span class="text-muted">2003</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-globe fa-2x text-warning me-3"></i>
                                <div>
                                    <strong class="d-block">Presencia</strong>
                                    <span class="text-muted">Global</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Misión, Visión y Valores -->
    <section class="mvv-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Pilares</span>
                <h2 class="display-5 fw-bold mt-2">Misión, Visión y Valores</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card mvv-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mvv-icon mb-3">
                                <i class="fas fa-bullseye fa-3x text-warning"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Misión</h4>
                            <p class="card-text text-muted">Proporcionar servicios industriales especializados de máxima calidad, garantizando la seguridad de nuestro equipo y la satisfacción total de nuestros clientes en cada proyecto.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mvv-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mvv-icon mb-3">
                                <i class="fas fa-eye fa-3x text-warning"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Visión</h4>
                            <p class="card-text text-muted">Ser la empresa de referencia en servicios industriales para el sector Oil & Gas, destacando por nuestra excelencia operativa y compromiso con la innovación continua.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mvv-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mvv-icon mb-3">
                                <i class="fas fa-gem fa-3x text-warning"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Valores</h4>
                            <p class="card-text text-muted">Seguridad, Calidad, Compromiso, Innovación y Trabajo en Equipo son los pilares que guían cada una de nuestras operaciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipo -->
    <section class="team-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestro Equipo</span>
                <h2 class="display-5 fw-bold mt-2">Profesionales Altamente Capacitados</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Contamos con un equipo multidisciplinario de profesionales certificados y con amplia experiencia en el sector industrial.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="team-icon mb-3 mx-auto">
                                <i class="fas fa-user-tie fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Dirección</h5>
                            <p class="card-text text-muted small">Gestión estratégica y liderazgo operativo con más de 20 años de experiencia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="team-icon mb-3 mx-auto">
                                <i class="fas fa-diving-mask fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Buzos Industriales</h5>
                            <p class="card-text text-muted small">Profesionales certificados en buceo comercial y operaciones subsea.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="team-icon mb-3 mx-auto">
                                <i class="fas fa-rope fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Técnicos IRATA</h5>
                            <p class="card-text text-muted small">Especialistas certificados en acceso por cuerda para trabajos en altura.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="team-icon mb-3 mx-auto">
                                <i class="fas fa-hard-hat fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Ingenieros</h5>
                            <p class="card-text text-muted small">Equipo técnico especializado en estudios y análisis de integridad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Certificaciones -->
    <section class="certifications-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop" alt="Certificaciones" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="text-warning fw-bold text-uppercase">Certificaciones</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Estándares Internacionales</h2>
                    <p class="text-muted mb-4">EGIDRA cuenta con las certificaciones más exigentes del sector, garantizando la calidad y seguridad en todas nuestras operaciones.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-certificate fa-2x text-warning me-3"></i>
                                <span class="fw-bold">ISO 9001</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-shield-alt fa-2x text-warning me-3"></i>
                                <span class="fw-bold">ISO 14001</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-hard-hat fa-2x text-warning me-3"></i>
                                <span class="fw-bold">ISO 45001</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                                <i class="fas fa-check-circle fa-2x text-warning me-3"></i>
                                <span class="fw-bold">IRATA</span>
                            </div>
                        </div>
                    </div>
                    <a href="../seguridad/" class="btn btn-dark mt-4">
                        <i class="fas fa-shield-virus me-2"></i>Ver Seguridad HSE
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
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

    <!-- CTA -->
    <section class="cta-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-dark border-0 rounded-3 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-7 p-5">
                                <span class="text-warning fw-bold text-uppercase">¿Necesita nuestros servicios?</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">Contáctenos</h2>
                                <p class="text-white-50 mb-4">Nuestro equipo está listo para atender sus necesidades. Cuéntenos sobre su proyecto y le ofreceremos la mejor solución.</p>
                                <a href="../contacto/" class="btn btn-warning btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Contactar
                                </a>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-handshake fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">Trabajemos Juntos</h3>
                                    <p class="text-dark">Su éxito es nuestro compromiso.</p>
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
</body>
</html>