<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Valores'; $pageBreadcrumb = 'Valores Corporativos';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Valores — EGIDRA Editor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/valores/valores.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-valor" placeholder="Buscar valor..."></div>
                <span id="stats-val" style="font-size:.75rem;color:var(--muted);"></span>
            </div>
            <button class="btn-pri" id="btn-nuevo-val"><i class="fas fa-plus"></i>Nuevo valor</button>
        </div>
        <div class="row g-3" id="grid-valores">
            <div class="col-12 text-center text-muted py-4">
                <i class="fas fa-spinner fa-spin me-2"></i>Cargando...
            </div>
        </div>
    </main>
</div>

<!-- Modal Valor -->
<div class="modal-backdrop-custom" id="modal-valor">
    <div class="modal-box">
        <div class="modal-head">
            <h6 id="modal-val-title">Valor corporativo</h6>
            <button class="modal-close" data-modal-close="modal-valor"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="val-id">
            <div class="mb-3">
                <label class="f-label">Título <span class="text-danger">*</span></label>
                <input class="f-input" type="text" id="val-titulo" placeholder="Ej: Seguridad">
            </div>
            <div class="mb-3">
                <label class="f-label">Descripción</label>
                <textarea class="f-textarea" id="val-desc" placeholder="Descripción del valor corporativo..."></textarea>
            </div>
            <div class="mb-3">
                <label class="f-label">Icono <span style="color:var(--muted);font-weight:400">(clase Font Awesome)</span></label>
                <div class="d-flex gap-2">
                    <input class="f-input" type="text" id="val-icono" placeholder="fa-shield-halved">
                    <div class="icono-preview"><i class="fas fa-shield-halved" id="val-icono-prev"></i></div>
                </div>
            </div>
            <div class="mb-3">
                <label class="f-label">Orden</label>
                <input class="f-input" type="number" id="val-orden" min="0" max="99" placeholder="0">
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <label class="f-label mb-0">Activo</label>
                <label class="toggle-sw"><input type="checkbox" id="val-activo" checked><span class="toggle-slider"></span></label>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-valor">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-val"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/valores/valores.js"></script>
</body></html>
