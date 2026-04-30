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
    <title>Contacto - <?php echo $empNombre; ?></title>
    <meta name="description" content="Contacte con <?php echo $empNombre; ?> para servicios industriales especializados en Oil &amp; Gas. Disponibles 24/7 para atender su consulta.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/contacto/contacto.css">
</head>
<body>

    <?php include "../include/header.php"; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row min-vh-50 align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3">Estamos Aquí</span>
                    <h1 class="display-3 fw-bold text-white mb-4"><span class="text-warning">Contacto</span></h1>
                    <p class="lead text-white-50">Nuestro equipo técnico está disponible 24/7. Cuéntenos su proyecto y le responderemos en menos de 24 horas.</p>
                </div>
            </div>
        </div>
        <div class="header-scroll-indicator">
            <a href="#contacto" class="text-white">
                <i class="fas fa-chevron-down bounce"></i>
            </a>
        </div>
    </section>

    <!-- Formulario + Info -->
    <section id="contacto" class="contact-section">
        <div class="container">
            <div class="row g-5 align-items-start">

                <!-- Formulario -->
                <div class="col-lg-7">
                    <div class="card contact-form-card p-4 p-md-5">
                        <h2 class="fw-bold mb-1">Envíenos un Mensaje</h2>
                        <p class="text-muted mb-4">Rellene el formulario y nos pondremos en contacto con usted a la brevedad.</p>

                        <form novalidate id="contact-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre" name="nombre" class="form-control"
                                           placeholder="Juan García" required autocomplete="name">
                                    <div class="invalid-feedback">Por favor ingrese su nombre.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           placeholder="juan@empresa.com" required autocomplete="email">
                                    <div class="invalid-feedback">Ingrese un correo válido.</div>
                                </div>
                                <div class="col-12">
                                    <label for="asunto" class="form-label">Asunto</label>
                                    <select id="asunto" name="asunto" class="form-select">
                                        <option value="">Seleccione un asunto...</option>
                                        <option>Información general</option>
                                        <option>Otro</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="mensaje" class="form-label">Mensaje <span class="text-danger">*</span></label>
                                    <textarea id="mensaje" name="mensaje" class="form-control" rows="5"
                                              placeholder="Describa su proyecto o consulta..." required></textarea>
                                    <div class="invalid-feedback">Por favor escriba su mensaje.</div>
                                </div>
                                <div class="col-12">
                                    <div id="form-alert" class="d-none" role="alert"></div>
                                </div>
                                <div class="col-12 mt-1">
                                    <button type="submit" class="btn-submit" id="submit-btn">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info de contacto (JS renderiza) -->
                <div class="col-lg-5">
                    <h2 class="fw-bold mb-1">Información de Contacto</h2>
                    <p class="text-muted mb-4">Múltiples canales para atenderle cuando lo necesite.</p>
                    <div class="d-flex flex-column gap-3" id="contactInfoCards">
                        <!-- Skeleton -->
                        <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="card shadow-sm placeholder-glow">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <span class="placeholder rounded-circle flex-shrink-0" style="width:48px;height:48px;"></span>
                                <div class="flex-grow-1">
                                    <span class="placeholder col-4 d-block mb-2" style="height:14px;"></span>
                                    <span class="placeholder col-7 d-block mb-1" style="height:11px;"></span>
                                    <span class="placeholder col-5 d-block" style="height:11px;"></span>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Mapa -->
    <section class="map-section">
        <div class="container">
            <div class="text-center mb-4">
                <span class="text-warning fw-bold text-uppercase">Ubicación</span>
                <h2 class="display-6 fw-bold mt-1">Dónde Estamos</h2>
            </div>
            <div class="ratio" style="--bs-aspect-ratio: 45%;">
                <iframe
                    src="https://maps.google.com/maps?q=3.7479676,8.7713486&z=15&output=embed"
                    style="border:0; border-radius: 12px;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="text-center mt-3">
                <a href="https://www.google.com/maps/@3.7479676,8.7713486,15z"
                   target="_blank" rel="noopener" class="btn btn-warning btn-sm px-4">
                    <i class="fas fa-external-link-alt me-1"></i>Abrir en Google Maps
                </a>
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
                        <i class="fas fa-headset mb-2"></i>
                        <h2 class="stat-number" data-target="24">0</h2>
                        <p class="text-muted mb-0">Soporte 24h</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "../include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../recursos/js/contacto/main.js"></script>
</body>
</html>
