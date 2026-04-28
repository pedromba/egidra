<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Empresa'; $pageBreadcrumb = 'Configuración Empresarial';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Empresa — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/empresa/empresa.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="row g-3">

            <!-- Identidad -->
            <div class="col-lg-8">
                <div class="card-admin">
                    <div class="card-head">
                        <h6><i class="fas fa-building-columns me-2" style="color:var(--primary)"></i>Identidad Corporativa</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="f-label">Nombre <span class="text-danger">*</span></label>
                                <input class="f-input" id="emp-nombre" type="text" placeholder="EGIDRA">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Año de fundación</label>
                                <input class="f-input" id="emp-anio" type="number" min="1900" max="2099" placeholder="Ej: 2010">
                            </div>
                            <div class="col-12">
                                <label class="f-label">Slogan</label>
                                <input class="f-input" id="emp-slogan" type="text" placeholder="Ej: Soluciones submarinas de excelencia">
                            </div>
                            <div class="col-12">
                                <label class="f-label">Descripción (Sobre Nosotros)</label>
                                <textarea class="f-textarea" id="emp-desc" rows="4" placeholder="Párrafo principal sobre la empresa..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logos -->
            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-image me-2" style="color:var(--primary)"></i>Logos</h6>
                    </div>
                    <div class="card-body-p">
                        <!-- Logo Principal -->
                        <div class="mb-3">
                            <label class="f-label">Logo Principal</label>
                            <div class="logo-preview-box mb-2" id="logo-preview-color">
                                <div class="logo-ph"><i class="fas fa-image"></i><span>Logo principal</span></div>
                            </div>
                            <div class="file-upload-box">
                                <input type="file" id="emp-logo-file" accept="image/*" class="d-none">
                                <button type="button" class="btn-upload" id="btn-upload-logo">
                                    <i class="fas fa-cloud-arrow-up me-1"></i>Subir Logo
                                </button>
                                <div class="file-info text-center text-muted" style="font-size:.75rem; margin-top:.5rem;">
                                    <small id="logo-file-name">Ningún archivo seleccionado</small>
                                </div>
                            </div>
                        </div>

                        <!-- Logo Blanco -->
                        <div class="mb-3">
                            <label class="f-label">Logo Blanco (Fondo Oscuro)</label>
                            <div class="logo-preview-box logo-dark mb-2" id="logo-preview-white">
                                <div class="logo-ph logo-ph-dark"><i class="fas fa-image"></i><span>Logo blanco</span></div>
                            </div>
                            <div class="file-upload-box">
                                <input type="file" id="emp-logo-blanco-file" accept="image/*" class="d-none">
                                <button type="button" class="btn-upload" id="btn-upload-logo-blanco">
                                    <i class="fas fa-cloud-arrow-up me-1"></i>Subir Logo Blanco
                                </button>
                                <div class="file-info text-center text-muted" style="font-size:.75rem; margin-top:.5rem;">
                                    <small id="logo-blanco-file-name">Ningún archivo seleccionado</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Misión -->
            <div class="col-md-6">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-bullseye me-2" style="color:var(--primary)"></i>Misión</h6>
                    </div>
                    <div class="card-body-p">
                        <textarea class="f-textarea" id="emp-mision" rows="5" placeholder="Declaración de misión de la empresa..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Visión -->
            <div class="col-md-6">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-eye me-2" style="color:var(--primary)"></i>Visión</h6>
                    </div>
                    <div class="card-body-p">
                        <textarea class="f-textarea" id="emp-vision" rows="5" placeholder="Declaración de visión de la empresa..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-head">
                        <h6><i class="fas fa-address-book me-2" style="color:var(--primary)"></i>Información de Contacto</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="f-label">Email</label>
                                <input class="f-input" id="emp-email" type="email" placeholder="info@egidra.com">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Teléfono</label>
                                <input class="f-input" id="emp-tel" type="tel" placeholder="+240 222 000 000">
                            </div>
                            <div class="col-12">
                                <label class="f-label">Dirección</label>
                                <textarea class="f-textarea" id="emp-dir" rows="2" placeholder="Dirección completa..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Ciudad</label>
                                <input class="f-input" id="emp-ciudad" type="text" placeholder="Malabo">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">País</label>
                                <input class="f-input" id="emp-pais" type="text" placeholder="Guinea Ecuatorial">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Redes Sociales -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-head">
                        <h6><i class="fas fa-share-nodes me-2" style="color:var(--primary)"></i>Redes Sociales</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="f-label"><i class="fab fa-linkedin me-1" style="color:#0a66c2"></i>LinkedIn</label>
                                <input class="f-input" id="emp-linkedin" type="url" placeholder="https://linkedin.com/company/egidra">
                            </div>
                            <div class="col-12">
                                <label class="f-label"><i class="fab fa-facebook me-1" style="color:#1877f2"></i>Facebook</label>
                                <input class="f-input" id="emp-facebook" type="url" placeholder="https://facebook.com/egidra">
                            </div>
                            <div class="col-12">
                                <label class="f-label"><i class="fab fa-instagram me-1" style="color:#e1306c"></i>Instagram</label>
                                <input class="f-input" id="emp-instagram" type="url" placeholder="https://instagram.com/egidra">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardar -->
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn-sec" id="btn-reset-empresa"><i class="fas fa-rotate-left me-1"></i>Restablecer</button>
                    <button class="btn-pri" id="btn-guardar-empresa"><i class="fas fa-check me-1"></i>Guardar cambios</button>
                </div>
            </div>

        </div><!-- /row -->
    </main>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/empresa/empresa.js"></script>
</body></html>
