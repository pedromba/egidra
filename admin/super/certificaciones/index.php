<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Certificaciones'; $pageBreadcrumb = 'Certificaciones';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Certificaciones — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/certificaciones/certificaciones.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="f-search" style="width:260px;"><i class="fas fa-search"></i><input type="text" id="search-cert" placeholder="Buscar certificación..."></div>
            <button class="btn-pri" id="btn-nueva-cert"><i class="fas fa-plus"></i>Nueva certificación</button>
        </div>
        <div class="card-admin">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead><tr>
                        <th>Certificación</th>
                        <th>Organismo</th>
                        <th>Año</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th></th>
                    </tr></thead>
                    <tbody id="tbody-certs">
                        <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-cert">
    <div class="modal-box" style="max-width:540px;">
        <div class="modal-head">
            <h6 id="modal-cert-title">Certificación</h6>
            <button class="modal-close" data-modal-close="modal-cert"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="cert-id">
            <div class="row g-3">
                <div class="col-12">
                    <label class="f-label">Nombre <span class="text-danger">*</span></label>
                    <input class="f-input" id="cert-nombre" type="text" placeholder="Ej: ISO 9001:2015 — Calidad">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Organismo emisor</label>
                    <input class="f-input" id="cert-org" type="text" placeholder="Ej: Bureau Veritas">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Año de obtención</label>
                    <input class="f-input" id="cert-anio" type="number" min="1990" max="2099" placeholder="2024">
                </div>
                <div class="col-12">
                    <label class="f-label">Descripción</label>
                    <textarea class="f-textarea" id="cert-desc" placeholder="Alcance y descripción de la certificación..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="f-label">Logo (ruta)</label>
                    <input class="f-input" id="cert-logo" type="text" placeholder="/img/certs/iso9001.svg">
                </div>
                <div class="col-md-6">
                    <label class="f-label">URL de verificación</label>
                    <input class="f-input" id="cert-url" type="url" placeholder="https://...">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Fecha de vencimiento</label>
                    <input class="f-input" id="cert-vence" type="date">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Orden</label>
                    <input class="f-input" id="cert-orden" type="number" min="0" max="99" placeholder="0">
                </div>
                <div class="col-12 d-flex align-items-center justify-content-between">
                    <label class="f-label mb-0">Publicada</label>
                    <label class="toggle-sw"><input type="checkbox" id="cert-activo" checked><span class="toggle-slider"></span></label>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-cert">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-cert"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/certificaciones/certificaciones.js"></script>
</body></html>
