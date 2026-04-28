<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGIDRA - Servicios Industriales Especializados</title>
    <meta name="description" content="EGIDRA - Empresa líder en servicios industriales: Buceo, Acceso por Cuerda, Logística y Estudios Técnicos para el sector Oil & Gas.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="recursos/css/inicio/index.css">
</head>
<body>

    <!-- Navbar -->
    <?php include "include/header.php"; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row min-vh-100 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Líder en Servicios Industriales</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Expertos en Soluciones <span class="text-warning">Industriales</span></h1>
                    <p class="lead text-white-50 mb-5">Más de 20 años de experiencia proporcionando servicios especializados de buceo, acceso por cuerda, logística y estudios técnicos para el sector Oil & Gas.</p>
                    <div class="d-flex gap-3">
                        <a href="servicios/" class="btn btn-warning btn-lg px-4">
                            <i class="fas fa-hard-hat me-2"></i>Nuestros Servicios
                        </a>
                        <a href="contacto/" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-envelope me-2"></i>Contactar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <a href="#stats" class="text-white">
                <i class="fas fa-chevron-down fa-2x bounce"></i>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats-section py-4">
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
                        <i class="fas fa-certificate mb-2"></i>
                        <h2 class="stat-number" data-target="100">0</h2>
                        <p class="text-muted mb-0">Seguridad</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros Preview -->
    <section class="about-preview py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&h=400&fit=crop" alt="Equipo EGIDRA" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <span class="text-warning fw-bold text-uppercase">Sobre Nosotros</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Compromiso con la Excelencia Industrial</h2>
                    <p class="text-muted mb-4">EGIDRA es una empresa líder en servicios industriales especializados, comprometida con los más altos estándares de calidad y seguridad en cada proyecto que ejecutamos.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle text-warning me-3"></i>Personal altamente capacitado y certificado</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-warning me-3"></i>Equipamiento de última generación</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-warning me-3"></i>Certificaciones internacionales en seguridad</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-warning me-3"></i>Presencia en múltiples regiones petroleras</li>
                    </ul>
                    <a href="sobre-nosotros/" class="btn btn-dark mt-3">
                        <i class="fas fa-arrow-right me-2"></i>Conocer Más
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Preview -->
    <section class="services-preview py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Servicios</span>
                <h2 class="display-5 fw-bold mt-2">Soluciones Integrales para la Industria</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Ofrecemos un portafolio completo de servicios especializados adaptados a las necesidades del sector Oil & Gas.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-diving-mask fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Buceo & Subsea</h5>
                            <p class="card-text text-muted">Servicios de buceo industrial, inspecciones subsea, reparaciones y mantenimiento de estructuras offshore.</p>
                            <a href="servicios/" class="text-warning text-decoration-none">Ver más <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-rope fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Acceso por Cuerda</h5>
                            <p class="card-text text-muted">Técnicas de rope access para inspecciones, mantenimiento y trabajos en altura extrema.</p>
                            <a href="servicios/" class="text-warning text-decoration-none">Ver más <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-truck-loading fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Logística</h5>
                            <p class="card-text text-muted">Gestión integral de logística para operaciones offshore y onshore, transporte y suministro.</p>
                            <a href="servicios/" class="text-warning text-decoration-none">Ver más <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-clipboard-check fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold">Estudios Técnicos</h5>
                            <p class="card-text text-muted">Estudios de ingeniería, análisis de integridad y evaluaciones técnicas especializadas.</p>
                            <a href="servicios/" class="text-warning text-decoration-none">Ver más <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seguridad HSE Preview -->
    <section class="hse-preview py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop" alt="Seguridad HSE" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="text-warning fw-bold text-uppercase">Seguridad HSE</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Las 9 Reglas de Oro</h2>
                    <p class="text-muted mb-4">Nuestra prioridad es la seguridad. Cumplimos estrictamente con las 9 Reglas de Oro que rigen todas nuestras operaciones.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-halved fa-2x text-warning me-3"></i>
                                <span>Trabajo en Altura</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hard-hat fa-2x text-warning me-3"></i>
                                <span>EPP Obligatorio</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-fire-extinguisher fa-2x text-warning me-3"></i>
                                <span>Control de Fuego</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bolt fa-2x text-warning me-3"></i>
                                <span>Energía Eléctrica</span>
                            </div>
                        </div>
                    </div>
                    <a href="seguridad/" class="btn btn-dark mt-4">
                        <i class="fas fa-shield-virus me-2"></i>Ver Certificaciones
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Proyectos/Clientes Preview -->
    <section class="projects-preview py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Proyectos Destacados</span>
                <h2 class="display-5 fw-bold mt-2">Nuestro Track Record</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Hemos ejecutado exitosamente proyectos para las principales empresas del sector Oil & Gas a nivel mundial.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card project-card border-0 shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1513828583688-c52646db42da?w=400&h=250&fit=crop" class="card-img-top" alt="Proyecto PLEM">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Buceo Industrial</span>
                            <h5 class="card-title fw-bold">Inspección PLEM</h5>
                            <p class="card-text text-muted small">Proyecto de inspección y mantenimiento de sistema PLEM en región offshore.</p>
                            <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> Pta. Europa</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card project-card border-0 shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=400&h=250&fit=crop" class="card-img-top" alt="Proyecto Rope Access">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Rope Access</span>
                            <h5 class="card-title fw-bold">Mantenimiento Plataforma</h5>
                            <p class="card-text text-muted small">Trabajos de inspección y mantenimiento mediante técnicas de acceso por cuerda.</p>
                            <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> Región Insular</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card project-card border-0 shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=400&h=250&fit=crop" class="card-img-top" alt="Proyecto Logística">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Logística</span>
                            <h5 class="card-title fw-bold">Suministro Offshore</h5>
                            <p class="card-text text-muted small">Gestión logística integral para operaciones de suministro a plataformas offshore.</p>
                            <span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> Múltiples Regiones</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="proyectos/" class="btn btn-dark btn-lg">
                    <i class="fas fa-folder-open me-2"></i>Ver Todos los Proyectos
                </a>
            </div>
        </div>
    </section>

    <!-- Clientes Logo Strip -->
    <section class="clients-strip py-4 bg-dark">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">MARATHON</h4>
                </div>
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">CHEVRON</h4>
                </div>
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">TRIDENT</h4>
                </div>
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">REPSOL</h4>
                </div>
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">CEPSA</h4>
                </div>
                <div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">BP</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Socios / Alianzas Estratégicas -->
    <?php
    $socios = [
        ['BV',   'Bureau Veritas', 'Organismo internacional líder en inspección, verificación y certificación industrial.',    'https://bureauveritas.com'],
        ['IMCA', 'IMCA',           'International Marine Contractors Association. Estándar global en operaciones submarinas.',  'https://imca-int.com'],
        ['IR',   'IRATA',          'Industrial Rope Access Trade Association. Certificación en acceso industrial con cuerdas.', 'https://irata.org'],
        ['DNV',  'DNV GL',         'Det Norske Veritas. Sociedad de clasificación y gestión de riesgos para la industria marítima.', 'https://dnv.com'],
    ];
    ?>
    <section class="partners-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Socios</span>
                <h2 class="display-5 fw-bold mt-2">Alianzas Estratégicas</h2>
                <p class="text-muted mx-auto" style="max-width:620px;">Trabajamos junto a los organismos internacionales más reconocidos para garantizar los más altos estándares de calidad y seguridad en cada operación.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach ($socios as [$ini, $nombre, $desc, $url]): ?>
                <div class="col-6 col-md-3">
                    <div class="partner-card">
                        <div class="partner-logo"><?php echo $ini; ?></div>
                        <div class="partner-name"><?php echo $nombre; ?></div>
                        <p class="partner-desc"><?php echo $desc; ?></p>
                        <a href="<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" class="partner-link">
                            <i class="fas fa-arrow-up-right-from-square me-1"></i>Ver sitio
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Separador con mensaje de confianza -->
            <div class="partners-trust mt-5">
                <div class="row align-items-center g-3">
                    <div class="col-md-1 text-center"><i class="fas fa-certificate fa-2x text-warning"></i></div>
                    <div class="col-md-11">
                        <p class="mb-0 text-muted" style="font-size:.85rem;">
                            Todas nuestras operaciones están respaldadas por certificaciones internacionales vigentes. EGIDRA es miembro activo de los principales organismos de la industria subsea y rope access.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto Preview -->
    <section class="contact-preview py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card bg-dark border-0 rounded-3 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-7 p-5">
                                <span class="text-warning fw-bold text-uppercase">Contacto</span>
                                <h2 class="display-5 fw-bold text-white mt-2 mb-4">¿Necesita nuestros servicios?</h2>
                                <p class="text-white-50 mb-4">Contáctenos para discutir sus necesidades. Nuestro equipo técnico está disponible 24/7.</p>
                                <div class="mb-3">
                                    <i class="fas fa-phone text-warning me-3"></i>
                                    <span class="text-white">+240 222 XXX XXX</span>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-envelope text-warning me-3"></i>
                                    <span class="text-white">info@egidra.com</span>
                                </div>
                                <div class="mb-4">
                                    <i class="fas fa-map-marker-alt text-warning me-3"></i>
                                    <span class="text-white">Malabo, Guinea Ecuatorial</span>
                                </div>
                                <a href="contacto/" class="btn btn-warning btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                </a>
                            </div>
                            <div class="col-lg-5 bg-warning d-flex align-items-center justify-content-center">
                                <div class="text-center p-4">
                                    <i class="fas fa-headset fa-5x text-dark mb-3"></i>
                                    <h3 class="fw-bold text-dark">Soporte 24/7</h3>
                                    <p class="text-dark">Nuestro equipo de emergencia está disponible las 24 horas.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include "./include/footer.php"; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./recursos/js/inicio/main.js"></script>
</body>
</html>