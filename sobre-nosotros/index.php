<?php
require_once '../config/rutas.php';
require_once '../config/conexion.php';

$empresa = $conexion->query(
    "SELECT nombre, descripcion, mision, vision, anio_fundacion FROM empresa WHERE id = 1 LIMIT 1"
)->fetch_assoc();

$empNombre   = htmlspecialchars($empresa['nombre']       ?? 'EGIDRA');
$empDesc     = htmlspecialchars($empresa['descripcion']  ?? '');
$empMision   = htmlspecialchars($empresa['mision']       ?? '');
$empVision   = htmlspecialchars($empresa['vision']       ?? '');
$empFundado  = $empresa['anio_fundacion'] ?? '2003';
$empAnios    = $empFundado ? (int)(date('Y') - $empFundado) : 20;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - <?php echo $empNombre; ?></title>
    <meta name="description" content="Conoce más sobre <?php echo $empNombre; ?>, empresa líder en servicios industriales especializados para el sector Oil &amp; Gas.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/sobre-nosotros/sobre-nosotros.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Quiénes Somos</span>
                    <h1 class="display-3 fw-bold text-white mb-4">Sobre <span class="text-warning"><?php echo $empNombre; ?></span></h1>
                    <p class="lead text-white-50"><?php echo $empDesc ?: 'Más de ' . $empAnios . ' años de trayectoria en servicios industriales especializados.'; ?></p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#historia" class="text-white"><i class="fas fa-chevron-down bounce"></i></a>
        </div>
    </section>

    <!-- Historia y Trayectoria -->
    <section id="historia" class="history-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&h=450&fit=crop" alt="Historia <?php echo $empNombre; ?>" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6">
                    <span class="text-warning fw-bold text-uppercase">Nuestra Historia</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Trayectoria e Innovación</h2>
                    <p class="text-muted mb-4"><?php echo $empDesc ?: $empNombre . ' nació con la visión de proporcionar servicios industriales de excelencia para las operaciones más exigentes del sector Oil & Gas.'; ?></p>
                    <div class="row g-3 mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check fa-2x text-warning me-3"></i>
                                <div>
                                    <strong class="d-block">Fundada</strong>
                                    <span class="text-muted"><?php echo $empFundado; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-globe fa-2x text-warning me-3"></i>
                                <div>
                                    <strong class="d-block">Experiencia</strong>
                                    <span class="text-muted"><?php echo $empAnios; ?>+ años</span>
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
                            <div class="mvv-icon mb-3"><i class="fas fa-bullseye fa-3x text-warning"></i></div>
                            <h4 class="card-title fw-bold mb-3">Misión</h4>
                            <p class="card-text text-muted"><?php echo $empMision ?: 'Proporcionar servicios industriales especializados de máxima calidad, garantizando la seguridad de nuestro equipo y la satisfacción total de nuestros clientes.'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mvv-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mvv-icon mb-3"><i class="fas fa-eye fa-3x text-warning"></i></div>
                            <h4 class="card-title fw-bold mb-3">Visión</h4>
                            <p class="card-text text-muted"><?php echo $empVision ?: 'Ser la empresa de referencia en servicios industriales para el sector Oil & Gas, destacando por nuestra excelencia operativa.'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mvv-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mvv-icon mb-3"><i class="fas fa-gem fa-3x text-warning"></i></div>
                            <h4 class="card-title fw-bold mb-3">Valores</h4>
                            <div id="valoresMVVResumen" class="card-text text-muted">
                                <span class="placeholder-glow"><span class="placeholder col-10"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valores Corporativos -->
    <section class="valores-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestros Valores</span>
                <h2 class="display-5 fw-bold mt-2">Lo que nos define</h2>
            </div>
            <div class="row g-4" id="valoresContainer">
                <div class="col-12 text-center py-4 placeholder-glow">
                    <span class="placeholder col-2 me-2"></span><span class="placeholder col-2 me-2"></span><span class="placeholder col-2"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipo -->
    <section class="team-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-warning fw-bold text-uppercase">Nuestro Equipo</span>
                <h2 class="display-5 fw-bold mt-2">Profesionales Altamente Capacitados</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Contamos con un equipo multidisciplinario de profesionales certificados y con amplia experiencia en el sector industrial.</p>
            </div>
            <div class="row g-4" id="equipoContainer">
                <div class="col-md-6 col-lg-3"><div class="card border-0 shadow-sm h-100 placeholder-glow"><div class="card-body text-center p-4"><div class="placeholder rounded-circle mx-auto mb-3" style="width:80px;height:80px;display:block;"></div><span class="placeholder col-8 d-block mb-2"></span><span class="placeholder col-6"></span></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="card border-0 shadow-sm h-100 placeholder-glow"><div class="card-body text-center p-4"><div class="placeholder rounded-circle mx-auto mb-3" style="width:80px;height:80px;display:block;"></div><span class="placeholder col-8 d-block mb-2"></span><span class="placeholder col-6"></span></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="card border-0 shadow-sm h-100 placeholder-glow"><div class="card-body text-center p-4"><div class="placeholder rounded-circle mx-auto mb-3" style="width:80px;height:80px;display:block;"></div><span class="placeholder col-8 d-block mb-2"></span><span class="placeholder col-6"></span></div></div></div>
                <div class="col-md-6 col-lg-3"><div class="card border-0 shadow-sm h-100 placeholder-glow"><div class="card-body text-center p-4"><div class="placeholder rounded-circle mx-auto mb-3" style="width:80px;height:80px;display:block;"></div><span class="placeholder col-8 d-block mb-2"></span><span class="placeholder col-6"></span></div></div></div>
            </div>
        </div>
    </section>

    <!-- Certificaciones -->
    <section class="certifications-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=400&fit=crop" alt="Certificaciones" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-lg-6 order-lg-1">
                    <span class="text-warning fw-bold text-uppercase">Certificaciones</span>
                    <h2 class="display-5 fw-bold mt-2 mb-4">Estándares Internacionales</h2>
                    <p class="text-muted mb-4"><?php echo $empNombre; ?> cuenta con las certificaciones más exigentes del sector, garantizando la calidad y seguridad en todas nuestras operaciones.</p>
                    <div class="row g-3" id="certificacionesContainer">
                        <div class="col-12 placeholder-glow">
                            <span class="placeholder col-3 me-2"></span><span class="placeholder col-3 me-2"></span><span class="placeholder col-3"></span>
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
                        <h2 class="stat-number" data-target="<?php echo $empAnios; ?>" data-key="anios"><?php echo $empAnios; ?></h2>
                        <p class="text-muted mb-0">Años</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4 mb-md-0">
                    <div class="stat-item">
                        <i class="fas fa-users mb-2"></i>
                        <h2 class="stat-number" data-target="0" data-key="clientes">0</h2>
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

    <?php include "../include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos/js/sobre-nosotros/main.js"></script>
</body>
</html>
