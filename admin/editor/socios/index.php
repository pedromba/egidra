<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Socios'; $pageBreadcrumb = 'Socios y Alianzas';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Socios — EGIDRA Editor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/socios/socios.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-socio" placeholder="Buscar socio..."></div>
                <span id="stats-socios" style="font-size:.75rem;color:var(--muted);"></span>
            </div>
            <button class="btn-pri" id="btn-nuevo-socio"><i class="fas fa-plus"></i>Nuevo socio</button>
        </div>
        <div class="card-admin" id="lista-socios">
            <div class="socio-row">
                <i class="fas fa-spinner fa-spin text-muted me-2"></i>
                <span class="text-muted">Cargando...</span>
            </div>
        </div>
    </main>
</div>

<!-- Modal Socio -->
<div class="modal-backdrop-custom" id="modal-socio">
    <div class="modal-box">
        <div class="modal-head">
            <h6 id="modal-socio-title">Socio / Alianza</h6>
            <button class="modal-close" data-modal-close="modal-socio"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="soc-id">
            <div class="mb-3">
                <label class="f-label">Nombre <span class="text-danger">*</span></label>
                <input class="f-input" type="text" id="soc-nombre" placeholder="Ej: Bureau Veritas">
            </div>
            <div class="mb-3">
                <label class="f-label">Descripción</label>
                <textarea class="f-textarea" id="soc-desc" placeholder="Descripción de la relación o del organismo..."></textarea>
            </div>
            <div class="mb-3">
                <label class="f-label">Logo</label>
                <div class="foto-upload-area" id="logo-area">
                    <div class="foto-preview-wrap" id="logo-preview-wrap" style="display:none;">
                        <img id="logo-preview" src="" alt="Logo" style="width:72px;height:72px;border-radius:8px;object-fit:contain;border:2px solid var(--border);background:#f8f9fa;padding:4px;">
                        <button type="button" class="foto-remove" id="logo-remove" title="Quitar logo"><i class="fas fa-times"></i></button>
                    </div>
                    <label class="foto-label" id="logo-label" for="soc-logo">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span id="logo-label-txt">Haz clic o arrastra un logo</span>
                        <small>PNG, SVG, JPG, WEBP · máx. 1 MB</small>
                    </label>
                    <input type="file" id="soc-logo" accept="image/png,image/jpeg,image/webp,image/svg+xml" style="display:none;">
                    <input type="hidden" id="soc-logo-actual">
                </div>
            </div>
            <div class="mb-3">
                <label class="f-label">Sitio web</label>
                <input class="f-input" type="url" id="soc-url" placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="f-label">Orden</label>
                <input class="f-input" type="number" id="soc-orden" min="0" max="99" placeholder="0">
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <label class="f-label mb-0">Activo</label>
                <label class="toggle-sw"><input type="checkbox" id="soc-activo" checked><span class="toggle-slider"></span></label>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-socio">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-soc"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/socios/socios.js"></script>
</body></html>
