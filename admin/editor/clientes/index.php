<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Clientes'; $pageBreadcrumb = 'Clientes';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Clientes — EGIDRA Editor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/clientes/clientes.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-client" placeholder="Buscar cliente..."></div>
                <span id="stats-cli" style="font-size:.75rem;color:var(--muted);"></span>
            </div>
            <button class="btn-pri" id="btn-nuevo-cli"><i class="fas fa-plus"></i>Nuevo cliente</button>
        </div>
        <div class="row g-3" id="grid-clientes">
            <div class="col-12 text-center text-muted py-4">
                <i class="fas fa-spinner fa-spin me-2"></i>Cargando...
            </div>
        </div>
    </main>
</div>

<!-- Modal Cliente -->
<div class="modal-backdrop-custom" id="modal-cli">
    <div class="modal-box">
        <div class="modal-head">
            <h6 id="modal-cli-title">Cliente</h6>
            <button class="modal-close" data-modal-close="modal-cli"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="cli-id">
            <div class="mb-3">
                <label class="f-label">Nombre <span class="text-danger">*</span></label>
                <input class="f-input" type="text" id="cli-nombre" placeholder="Ej: Marathon Oil">
            </div>
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <label class="f-label">Iniciales</label>
                    <input class="f-input" type="text" id="cli-iniciales" maxlength="3" placeholder="MA">
                </div>
                <div class="col-8">
                    <label class="f-label">Sector</label>
                    <input class="f-input" type="text" id="cli-sector" placeholder="Petróleo y Gas">
                </div>
            </div>
            <div class="mb-3">
                <label class="f-label">Descripción</label>
                <textarea class="f-textarea" id="cli-desc" placeholder="Relación comercial, sector..."></textarea>
            </div>
            <div class="mb-0 d-flex align-items-center justify-content-between">
                <label class="f-label mb-0">Activo</label>
                <label class="toggle-sw"><input type="checkbox" id="cli-activo" checked><span class="toggle-slider"></span></label>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-cli">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-cli"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/clientes/clientes.js"></script>
</body></html>
