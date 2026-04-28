<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - EGIDRA</title>
    <meta name="description" content="Contacte con EGIDRA para servicios industriales especializados en Oil & Gas. Disponibles 24/7 para atender su consulta.">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../recursos/css/inicio/index.css">
    <link rel="stylesheet" href="../recursos/css/contacto/contacto.css">
</head>
<body>

    <!-- Navbar -->
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

    <!-- ── Formulario + Info ──────────────────────────────────────── -->
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
                                           placeholder="Juan García" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           placeholder="juan@empresa.com" required>
                                </div>
                                <div class="col-12">
                                    <label for="asunto" class="form-label">Asunto</label>
                                    <select id="asunto" name="asunto" class="form-select">
                                        <option value="">Seleccione un asunto...</option>
                                        <option>Buceo &amp; Subsea</option>
                                        <option>Acceso por Cuerda</option>
                                        <option>Logística</option>
                                        <option>Estudios Técnicos</option>
                                        <option>Seguridad HSE</option>
                                        <option>Información general</option>
                                        <option>Otro</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="mensaje" class="form-label">Mensaje <span class="text-danger">*</span></label>
                                    <textarea id="mensaje" name="mensaje" class="form-control"
                                              placeholder="Describa su proyecto o consulta..." required></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn-submit" id="submit-btn">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info de contacto -->
                <div class="col-lg-5">

                    <h2 class="fw-bold mb-1">Información de Contacto</h2>
                    <p class="text-muted mb-4">Múltiples canales para atenderle cuando lo necesite.</p>

                    <div class="d-flex flex-column gap-3">

                        <div class="card info-card shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <div class="info-icon">
                                    <i class="fas fa-phone fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">Teléfono</p>
                                    <p class="text-muted small mb-0">+240 222 XXX XXX</p>
                                    <p class="text-muted small mb-0">Disponible 24/7 emergencias</p>
                                </div>
                            </div>
                        </div>

                        <div class="card info-card shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <div class="info-icon">
                                    <i class="fas fa-envelope fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">Correo Electrónico</p>
                                    <p class="text-muted small mb-0">info@egidra.com</p>
                                    <p class="text-muted small mb-0">Respuesta en &lt; 24 horas</p>
                                </div>
                            </div>
                        </div>

                        <div class="card info-card shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">Sede Principal</p>
                                    <p class="text-muted small mb-0">Malabo, Guinea Ecuatorial</p>
                                    <p class="text-muted small mb-0">Operaciones internacionales</p>
                                </div>
                            </div>
                        </div>

                        <div class="card info-card shadow-sm">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <div class="info-icon">
                                    <i class="fas fa-clock fa-lg text-warning"></i>
                                </div>
                                <div>
                                    <p class="fw-bold mb-0">Horario Oficina</p>
                                    <p class="text-muted small mb-0">Lun–Vie: 08:00 – 18:00</p>
                                    <p class="text-muted small mb-0">Guardia operativa 24/7</p>
                                </div>
                            </div>
                        </div>

                        <div class="card info-card shadow-sm">
                            <div class="card-body p-4">
                                <p class="fw-bold mb-3">Síguenos</p>
                                <div class="d-flex gap-3">
                                    <a href="#" class="btn btn-dark btn-sm px-3 py-2" aria-label="LinkedIn">
                                        <i class="fab fa-linkedin fa-lg"></i>
                                    </a>
                                    <a href="#" class="btn btn-dark btn-sm px-3 py-2" aria-label="Twitter">
                                        <i class="fab fa-twitter fa-lg"></i>
                                    </a>
                                    <a href="#" class="btn btn-dark btn-sm px-3 py-2" aria-label="Facebook">
                                        <i class="fab fa-facebook fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Mapa ───────────────────────────────────────────────────── -->
    <section class="map-section">
        <div class="container">
            <div class="text-center mb-4">
                <span class="text-warning fw-bold text-uppercase">Ubicación</span>
                <h2 class="display-6 fw-bold mt-1">Dónde Estamos</h2>
            </div>
            <div class="map-placeholder">
                <div class="text-center position-relative" style="z-index:1;">
                    <i class="fas fa-map-marked-alt fa-4x text-warning mb-3 d-block"></i>
                    <h5 class="text-white fw-bold mb-1">Malabo, Guinea Ecuatorial</h5>
                    <p class="text-white-50 small mb-3">Sede principal con operaciones a nivel internacional</p>
                    <a href="https://maps.google.com" target="_blank" rel="noopener"
                       class="btn btn-warning btn-sm px-4">
                        <i class="fas fa-external-link-alt me-1"></i>Abrir en Google Maps
                    </a>
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
                        <i class="fas fa-headset mb-2"></i>
                        <h2 class="stat-number" data-target="24">0</h2>
                        <p class="text-muted mb-0">Soporte 24h</p>
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
        const form = document.getElementById('contact-form');
        const btn  = document.getElementById('submit-btn');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            let valid = true;

            form.querySelectorAll('[required]').forEach(field => {
                field.classList.remove('is-invalid');
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                }
            });

            const email = document.getElementById('email');
            if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.classList.add('is-invalid');
                valid = false;
            }

            if (!valid) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        });
    })();
    </script>
</body>
</html>
