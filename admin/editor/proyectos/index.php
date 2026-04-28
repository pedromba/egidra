<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Proyectos'; $pageBreadcrumb = 'Proyectos';
$proyectos = [
    ['Inspección PLEM',           'buceo',   'Marathon','Pta. Europa',        2023, true ],
    ['Mantenimiento Plataforma',  'cuerda',  'Chevron', 'Región Insular',     2023, true ],
    ['Suministro Offshore',       'logist',  'Trident', 'Múltiples',          2022, true ],
    ['Instalación Riser',         'buceo',   'Repsol',  'Costa Atlántica',    2022, false],
    ['Análisis de Integridad',    'estudio', 'BP',      'Mar del Norte',      2023, false],
    ['Pintura Anticorrosiva',     'cuerda',  'Cepsa',   'Refinería Sur',      2022, false],
    ['Reemplazo Ánodos',          'buceo',   'Marathon','Terminal Cargadero', 2021, false],
    ['Movilización Campaña',      'logist',  'Trident', 'Guinea Ecuatorial',  2021, false],
    ['Estudio HAZOP',             'estudio', 'Repsol',  'Planta Onshore',     2024, false],
];
$labels=['buceo'=>['Buceo','bp-blue'],'cuerda'=>['Cuerda','bp-purple'],'logist'=>['Logística','bp-green'],'estudio'=>['Estudios','bp-yellow']];
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Proyectos — EGIDRA Editor</title>
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
                <button class="btn-sec" data-cat-filter="buceo">Buceo</button>
                <button class="btn-sec" data-cat-filter="cuerda">Cuerda</button>
                <button class="btn-sec" data-cat-filter="logist">Logística</button>
                <button class="btn-sec" data-cat-filter="estudio">Estudios</button>
            </div>
            <button class="btn-pri" data-modal-open="modal-add-proy"><i class="fas fa-plus"></i>Nuevo proyecto</button>
        </div>
        <div class="card-admin">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead><tr><th>Título</th><th>Categoría</th><th>Cliente</th><th>Ubicación</th><th>Año</th><th>Destacado</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($proyectos as [$t,$cat,$cli,$ubi,$ano,$dest]): ?>
                    <tr data-cat="<?php echo $cat; ?>">
                        <td style="font-weight:600;font-size:.82rem;"><?php echo $t; ?></td>
                        <td><span class="badge-pill <?php echo $labels[$cat][1]; ?>"><?php echo $labels[$cat][0]; ?></span></td>
                        <td style="font-size:.8rem;"><?php echo $cli; ?></td>
                        <td style="font-size:.78rem;color:var(--muted);"><i class="fas fa-map-marker-alt me-1"></i><?php echo $ubi; ?></td>
                        <td style="font-size:.8rem;"><?php echo $ano; ?></td>
                        <td><label class="toggle-sw"><input type="checkbox" <?php echo $dest?'checked':''; ?>><span class="toggle-slider"></span></label></td>
                        <td><div class="d-flex gap-1"><button class="btn-icon edit"><i class="fas fa-pen"></i></button><button class="btn-icon del"><i class="fas fa-trash"></i></button></div></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-add-proy">
    <div class="modal-box">
        <div class="modal-head"><h6>Nuevo Proyecto</h6><button class="modal-close" data-modal-close="modal-add-proy"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="f-label">Título <span class="text-danger">*</span></label><input class="f-input" type="text" placeholder="Ej: Inspección PLEM"></div>
            <div class="row g-2 mb-3">
                <div class="col-6"><label class="f-label">Categoría</label><select class="f-select"><option>Buceo Industrial</option><option>Rope Access</option><option>Logística</option><option>Estudios Técnicos</option></select></div>
                <div class="col-6"><label class="f-label">Cliente</label><select class="f-select"><option>Marathon</option><option>Chevron</option><option>Trident</option><option>Repsol</option><option>Cepsa</option><option>BP</option></select></div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-8"><label class="f-label">Ubicación</label><input class="f-input" type="text" placeholder="Ej: Malabo, GE"></div>
                <div class="col-4"><label class="f-label">Año</label><input class="f-input" type="number" placeholder="2026"></div>
            </div>
            <div class="mb-0"><label class="f-label">Descripción técnica</label><textarea class="f-textarea" placeholder="Detalles de ejecución..."></textarea></div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-add-proy">Cancelar</button><button class="btn-pri"><i class="fas fa-check me-1"></i>Crear</button></div>
    </div>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/proyectos/proyectos.js"></script>
</body></html>
