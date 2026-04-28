<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Valores'; $pageBreadcrumb = 'Valores Corporativos';
$valores = [
    ['fa-shield-halved', 'Seguridad',      'La seguridad de nuestro equipo es siempre la primera prioridad en cada operación.',                    true],
    ['fa-award',         'Excelencia',     'Buscamos los más altos estándares de calidad en todos nuestros servicios y entregables.',               true],
    ['fa-handshake',     'Integridad',     'Actuamos con honestidad y transparencia en todas nuestras relaciones comerciales y operativas.',        true],
    ['fa-leaf',          'Sostenibilidad', 'Comprometidos con el medio ambiente y el desarrollo sostenible en cada proyecto que ejecutamos.',       true],
    ['fa-lightbulb',     'Innovación',     'Adoptamos tecnología y metodologías avanzadas que mejoran continuamente la calidad de nuestro trabajo.',true],
];
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
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-valor" placeholder="Buscar valor..."></div>
            <button class="btn-pri" data-modal-open="modal-valor"><i class="fas fa-plus"></i>Nuevo valor</button>
        </div>
        <div class="row g-3">
            <?php foreach ($valores as $i => [$icon, $titulo, $desc, $activo]): ?>
            <div class="col-sm-6 col-lg-4 valor-card-wrap">
                <div class="valor-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="valor-icon"><i class="fas <?php echo $icon; ?>"></i></div>
                        <div>
                            <div class="valor-title"><?php echo $titulo; ?></div>
                            <span class="badge-pill <?php echo $activo ? 'bp-green' : 'bp-gray'; ?>"><?php echo $activo ? 'Activo' : 'Inactivo'; ?></span>
                        </div>
                    </div>
                    <div class="valor-desc"><?php echo $desc; ?></div>
                    <div class="valor-foot">
                        <span class="valor-order">Orden: <?php echo $i + 1; ?></span>
                        <div class="d-flex gap-1">
                            <button class="btn-icon edit" data-modal-open="modal-valor"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Modal Valor -->
<div class="modal-backdrop-custom" id="modal-valor">
    <div class="modal-box">
        <div class="modal-head">
            <h6>Valor corporativo</h6>
            <button class="modal-close" data-modal-close="modal-valor"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="f-label">Título <span class="text-danger">*</span></label>
                <input class="f-input" type="text" placeholder="Ej: Seguridad">
            </div>
            <div class="mb-3">
                <label class="f-label">Descripción</label>
                <textarea class="f-textarea" placeholder="Descripción del valor corporativo..."></textarea>
            </div>
            <div class="mb-3">
                <label class="f-label">Icono <span style="color:var(--muted);font-weight:400">(clase Font Awesome)</span></label>
                <div class="d-flex gap-2">
                    <input class="f-input" type="text" id="icono-input" placeholder="fa-shield-halved">
                    <div class="icono-preview"><i class="fas fa-shield-halved" id="icono-preview-i"></i></div>
                </div>
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
            <button class="btn-sec" data-modal-close="modal-valor">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/valores/valores.js"></script>
</body></html>
