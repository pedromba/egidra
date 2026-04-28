<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Socios'; $pageBreadcrumb = 'Socios y Alianzas';
$socios = [
    ['Bureau Veritas',  'BV', 'av-b', 'Organismo internacional líder en inspección, verificación, ensayo y certificación industrial.',    'https://bureauveritas.com', true],
    ['IMCA',            'IM', 'av-y', 'International Marine Contractors Association. Estándar global en operaciones submarinas offshore.', 'https://imca-int.com',     true],
    ['IRATA',           'IR', 'av-g', 'Industrial Rope Access Trade Association. Certificación y estándares en acceso con cuerdas.',       'https://irata.org',        true],
    ['DNV GL',          'DN', 'av-p', 'Det Norske Veritas. Sociedad de clasificación y gestión de riesgos para la industria marítima.',    'https://dnv.com',          true],
];
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
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-socio" placeholder="Buscar socio..."></div>
            <button class="btn-pri" data-modal-open="modal-socio"><i class="fas fa-plus"></i>Nuevo socio</button>
        </div>
        <div class="card-admin">
            <?php foreach ($socios as [$nombre, $ini, $av, $desc, $url, $activo]): ?>
            <div class="socio-row">
                <div class="socio-logo <?php echo $av; ?>"><?php echo $ini; ?></div>
                <div class="socio-info">
                    <div class="socio-name"><?php echo $nombre; ?></div>
                    <div class="socio-desc"><?php echo $desc; ?></div>
                </div>
                <div class="socio-meta">
                    <span class="badge-pill <?php echo $activo ? 'bp-green' : 'bp-gray'; ?>"><?php echo $activo ? 'Activo' : 'Inactivo'; ?></span>
                </div>
                <div class="socio-actions">
                    <?php if ($url): ?>
                    <a href="<?php echo $url; ?>" target="_blank" class="btn-icon view" title="Ver web"><i class="fas fa-arrow-up-right-from-square"></i></a>
                    <?php endif; ?>
                    <button class="btn-icon edit" data-modal-open="modal-socio"><i class="fas fa-pen"></i></button>
                    <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Modal Socio -->
<div class="modal-backdrop-custom" id="modal-socio">
    <div class="modal-box">
        <div class="modal-head">
            <h6>Socio / Alianza</h6>
            <button class="modal-close" data-modal-close="modal-socio"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="f-label">Nombre <span class="text-danger">*</span></label>
                <input class="f-input" type="text" placeholder="Ej: Bureau Veritas">
            </div>
            <div class="mb-3">
                <label class="f-label">Descripción</label>
                <textarea class="f-textarea" placeholder="Descripción de la relación o del organismo..."></textarea>
            </div>
            <div class="mb-3">
                <label class="f-label">Logo (ruta o URL)</label>
                <input class="f-input" type="text" placeholder="/img/socios/bureau-veritas.svg">
            </div>
            <div class="mb-3">
                <label class="f-label">Sitio web</label>
                <input class="f-input" type="url" placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="f-label">Orden</label>
                <input class="f-input" type="number" min="0" max="99" placeholder="0">
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <label class="f-label mb-0">Activo</label>
                <label class="toggle-sw"><input type="checkbox" checked><span class="toggle-slider"></span></label>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-socio">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/socios/socios.js"></script>
</body></html>
