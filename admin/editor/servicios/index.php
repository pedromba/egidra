<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Servicios'; $pageBreadcrumb = 'Servicios';
$cats = [
    ['fa-diving-mask',    'Buceo & Subsea',    ['Inspección Submarina','Mantenimiento y Reparación','Instalación Subsea']],
    ['fa-rope',           'Acceso por Cuerda', ['Inspección en Altura','Pintura Anticorrosiva','Mantenimiento Estructural']],
    ['fa-truck-loading',  'Logística',         ['Suministro Offshore','Almacenamiento y Distribución','Movilización Personal']],
    ['fa-clipboard-check','Estudios Técnicos', ['Análisis de Integridad','Ingeniería de Proyecto','Estudios de Riesgo']],
];
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Servicios — EGIDRA Editor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/servicios/servicios.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <p class="mb-0" style="font-size:.82rem;color:var(--muted);">4 categorías · 12 servicios activos</p>
            <button class="btn-pri" data-modal-open="modal-add-svc"><i class="fas fa-plus"></i>Nuevo servicio</button>
        </div>
        <div class="row g-3">
        <?php foreach ($cats as [$icon,$name,$items]): ?>
            <div class="col-md-6">
                <div class="svc-card">
                    <div class="svc-card-head">
                        <div class="svc-icon"><i class="fas <?php echo $icon; ?>"></i></div>
                        <h6><?php echo $name; ?></h6>
                        <button class="btn-icon edit ms-auto"><i class="fas fa-pen"></i></button>
                    </div>
                    <?php foreach ($items as $item): ?>
                    <div class="svc-item">
                        <span><?php echo $item; ?></span>
                        <div class="d-flex gap-1">
                            <button class="btn-icon edit"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-add-svc">
    <div class="modal-box">
        <div class="modal-head"><h6>Nuevo Servicio</h6><button class="modal-close" data-modal-close="modal-add-svc"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="f-label">Categoría</label><select class="f-select"><option>Buceo & Subsea</option><option>Acceso por Cuerda</option><option>Logística</option><option>Estudios Técnicos</option></select></div>
            <div class="mb-3"><label class="f-label">Título <span class="text-danger">*</span></label><input class="f-input" type="text" placeholder="Ej: Inspección ROV"></div>
            <div class="mb-0"><label class="f-label">Descripción</label><textarea class="f-textarea" placeholder="Descripción detallada..."></textarea></div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-add-svc">Cancelar</button><button class="btn-pri"><i class="fas fa-check me-1"></i>Crear</button></div>
    </div>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/servicios/servicios.js"></script>
</body></html>
