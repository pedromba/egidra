<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Proyectos'; $pageBreadcrumb = 'Proyectos';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Proyectos — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/proyectos/proyectos.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="d-flex gap-2 flex-wrap">
                <div class="f-search" style="width:220px;"><i class="fas fa-search"></i><input type="text" id="search-proy" placeholder="Buscar..."></div>
                <button class="btn-sec active" data-cat-filter="">Todos</button>
                <div id="filter-cats-proy" class="d-flex gap-2 flex-wrap"></div>
            </div>
            <button class="btn-pri" id="btn-nuevo-proy"><i class="fas fa-plus"></i>Nuevo proyecto</button>
        </div>
        <div class="card-admin">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead><tr><th>Título</th><th>Categoría</th><th>Cliente</th><th>Ubicación</th><th>Año</th><th>Destacado</th><th></th></tr></thead>
                    <tbody id="tbody-proyectos">
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-proy">
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-head"><h6 id="modal-proy-title">Nuevo Proyecto</h6><button class="modal-close" data-modal-close="modal-proy"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <input type="hidden" id="proy-id">
            <div class="mb-3"><label class="f-label">Título <span class="text-danger">*</span></label><input class="f-input" id="proy-titulo" type="text" placeholder="Ej: Inspección PLEM"></div>
            <div class="row g-2 mb-3">
                <div class="col-6"><label class="f-label">Categoría</label><select class="f-select" id="proy-cat"><option value="">— Seleccionar —</option></select></div>
                <div class="col-6"><label class="f-label">Cliente</label><select class="f-select" id="proy-cli"><option value="">— Seleccionar —</option></select></div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-8"><label class="f-label">Ubicación</label><input class="f-input" id="proy-ubicacion" type="text" placeholder="Ej: Malabo, GE"></div>
                <div class="col-4"><label class="f-label">Año</label><input class="f-input" id="proy-ano" type="number" placeholder="2026"></div>
            </div>
            <div class="mb-3"><label class="f-label">Descripción técnica</label><textarea class="f-textarea" id="proy-desc" placeholder="Detalles de ejecución..."></textarea></div>
            <div class="mb-3">
                <label class="f-label">Imagen del proyecto</label>
                <div class="img-upload-area" id="proy-img-area" onclick="document.getElementById('proy-img-file').click()" title="Clic para subir imagen">
                    <img id="proy-img-preview" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:8px;">
                    <div id="proy-img-ph" class="text-center">
                        <i class="fas fa-image fa-2x text-muted mb-1 d-block"></i>
                        <span class="text-muted" style="font-size:.75rem;">Clic para subir · JPG, PNG, WebP · máx. 2 MB</span>
                    </div>
                </div>
                <input type="file" id="proy-img-file" name="imagen_file" accept="image/jpeg,image/png,image/webp" style="display:none">
                <input type="hidden" id="proy-img-actual" name="imagen_actual">
            </div>
            <div class="d-flex gap-4">
                <div class="d-flex align-items-center justify-content-between flex-grow-1"><label class="f-label mb-0">Destacado</label><label class="toggle-sw"><input type="checkbox" id="proy-dest"><span class="toggle-slider"></span></label></div>
                <div class="d-flex align-items-center justify-content-between flex-grow-1"><label class="f-label mb-0">Activo</label><label class="toggle-sw"><input type="checkbox" id="proy-activo" checked><span class="toggle-slider"></span></label></div>
            </div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-proy">Cancelar</button><button class="btn-pri" id="btn-guardar-proy"><i class="fas fa-check me-1"></i>Guardar</button></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/proyectos/proyectos.js"></script>
</body></html>
