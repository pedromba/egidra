<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Equipo'; $pageBreadcrumb = 'Gestión del Equipo';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Equipo — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/equipo/equipo.css">
</head>
<body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-equipo" placeholder="Buscar miembro..."></div>
            <button class="btn-pri" id="btn-nuevo-miembro"><i class="fas fa-plus"></i>Nuevo miembro</button>
        </div>
        <div class="row g-3" id="grid-equipo">
            <div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando equipo...</div>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-miembro">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-head"><h6 id="modal-miembro-title">Miembro del equipo</h6><button class="modal-close" data-modal-close="modal-miembro"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <input type="hidden" id="eq-id">
            <div class="row g-3">
                <div class="col-md-8"><label class="f-label">Nombre completo <span class="text-danger">*</span></label><input class="f-input" id="eq-nombre" type="text" placeholder="Ej: Juan Carlos Eworo"></div>
                <div class="col-md-4"><label class="f-label">Iniciales</label><input class="f-input" id="eq-ini" type="text" maxlength="3" placeholder="JC"></div>
                <div class="col-12"><label class="f-label">Cargo</label><input class="f-input" id="eq-cargo" type="text" placeholder="Ej: Director de Operaciones"></div>
                <div class="col-12"><label class="f-label">Biografía</label><textarea class="f-textarea" id="eq-bio" placeholder="Experiencia y especialización..."></textarea></div>
                <div class="col-12">
                    <label class="f-label">Foto</label>
                    <div class="foto-upload-area" id="foto-area">
                        <div class="foto-preview-wrap" id="foto-preview-wrap" style="display:none;">
                            <img id="foto-preview" src="" alt="Vista previa" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--border);">
                            <button type="button" class="foto-remove" id="foto-remove" title="Quitar foto"><i class="fas fa-times"></i></button>
                        </div>
                        <label class="foto-label" id="foto-label" for="eq-foto">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="foto-label-txt">Haz clic o arrastra una imagen</span>
                            <small>JPG, PNG, WEBP · máx. 2 MB</small>
                        </label>
                        <input type="file" id="eq-foto" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;">
                        <input type="hidden" id="eq-foto-actual">
                    </div>
                </div>
                <div class="col-12"><label class="f-label"><i class="fab fa-linkedin me-1" style="color:#0a66c2"></i>LinkedIn</label><input class="f-input" id="eq-linkedin" type="url" placeholder="https://linkedin.com/in/..."></div>
                <div class="col-md-4"><label class="f-label">Orden</label><input class="f-input" id="eq-orden" type="number" min="0" max="99" placeholder="0"></div>
                <div class="col-md-8 d-flex align-items-center justify-content-end gap-3 mt-2"><label class="f-label mb-0">Activo</label><label class="toggle-sw"><input type="checkbox" id="eq-activo" checked><span class="toggle-slider"></span></label></div>
            </div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-miembro">Cancelar</button><button class="btn-pri" id="btn-guardar-miembro"><i class="fas fa-check me-1"></i>Guardar</button></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/equipo/equipo.js"></script>
</body>
</html>
