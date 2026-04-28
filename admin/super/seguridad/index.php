<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Seguridad HSE'; $pageBreadcrumb = 'Seguridad HSE';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Seguridad HSE — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/seguridad/seguridad.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">

        <!-- 9 Reglas de Oro -->
        <div class="card-admin">
            <div class="card-head">
                <h6><i class="fas fa-shield-halved me-2" style="color:var(--primary)"></i>Las 9 Reglas de Oro</h6>
                <span style="font-size:.75rem;color:var(--muted);">Cumplimiento obligatorio e innegociable</span>
            </div>
            <div id="list-reglas">
                <div class="rule-row"><div class="text-center text-muted py-4 w-100"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div></div>
            </div>
        </div>

        <!-- Cifras HSE -->
        <div class="card-admin mt-3">
            <div class="card-head">
                <h6><i class="fas fa-chart-simple me-2" style="color:var(--primary)"></i>Cifras HSE</h6>
                <button class="btn-pri" id="btn-nueva-stat" style="font-size:.75rem;padding:6px 12px;">
                    <i class="fas fa-plus"></i>Nueva cifra
                </button>
            </div>
            <div class="row g-0" id="grid-stats">
                <div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>
            </div>
        </div>

    </main>
</div>

<!-- Modal Editar Regla -->
<div class="modal-backdrop-custom" id="modal-edit-rule">
    <div class="modal-box">
        <div class="modal-head">
            <h6>Editar Regla de Oro</h6>
            <button class="modal-close" data-modal-close="modal-edit-rule"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="reg-id">
            <div class="mb-3"><label class="f-label">Título</label><input class="f-input" id="reg-titulo" type="text" placeholder="Título de la regla"></div>
            <div class="mb-3"><label class="f-label">Icono (clase FA)</label><input class="f-input" id="reg-icono" type="text" placeholder="fa-person-falling-burst"></div>
            <div class="mb-0"><label class="f-label">Descripción</label><textarea class="f-textarea" id="reg-desc" placeholder="Descripción de la regla..."></textarea></div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-edit-rule">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-regla"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<!-- Modal Cifra HSE -->
<div class="modal-backdrop-custom" id="modal-stat">
    <div class="modal-box">
        <div class="modal-head">
            <h6 id="modal-stat-title">Cifra HSE</h6>
            <button class="modal-close" data-modal-close="modal-stat"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="stat-id">
            <div class="mb-3">
                <label class="f-label">Valor <span class="text-danger">*</span></label>
                <input class="f-input" id="stat-valor" type="text" placeholder="Ej: 500+, 0, 100%">
            </div>
            <div class="mb-3">
                <label class="f-label">Etiqueta <span class="text-danger">*</span></label>
                <input class="f-input" id="stat-etiqueta" type="text" placeholder="Descripción de la cifra">
            </div>
            <div class="mb-3">
                <label class="f-label">Icono (clase FA)</label>
                <input class="f-input" id="stat-icono" type="text" placeholder="fa-clock">
            </div>
            <div class="mb-0">
                <label class="f-label">Orden</label>
                <input class="f-input" id="stat-orden" type="number" min="0" max="99" placeholder="0">
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-stat">Cancelar</button>
            <button class="btn-pri" id="btn-guardar-stat"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/seguridad/seguridad.js"></script>
</body></html>
