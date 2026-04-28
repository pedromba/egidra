<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Equipo'; $pageBreadcrumb = 'Gestión del Equipo';
$equipo = [
    ['Juan Carlos Eworo', 'JC', 'av-y', 'Director de Operaciones',  'Más de 15 años en operaciones submarinas en el Golfo de Guinea. Certificado IMCA y IRATA.', 'https://linkedin.com', true],
    ['María Nsue Obiang', 'MN', 'av-b', 'Jefa de Seguridad HSE',    'Especialista certificada IRATA Nivel 3 e IMCA Diver. Responsable del sistema de gestión de seguridad.', null, true],
    ['Pedro Mba Ondo',    'PM', 'av-g', 'Supervisor de Buceo',      'Buzo profesional con certificación IMCA y más de 500 horas de inmersión en proyectos offshore.', 'https://linkedin.com', true],
    ['Clara Ebang Nze',   'CE', 'av-p', 'Coordinadora de Logística','Gestión integral de la cadena logística y aprovisionamiento para proyectos en alta mar.', null, true],
];
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Equipo — EGIDRA Editor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/equipo/equipo.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-equipo" placeholder="Buscar miembro..."></div>
            <button class="btn-pri" data-modal-open="modal-miembro"><i class="fas fa-plus"></i>Nuevo miembro</button>
        </div>
        <div class="row g-3">
            <?php foreach ($equipo as [$nombre, $ini, $av, $cargo, $bio, $linkedin, $activo]): ?>
            <div class="col-sm-6 col-lg-4 col-xl-3 team-card-wrap">
                <div class="team-card">
                    <div class="team-avatar <?php echo $av; ?>"><?php echo $ini; ?></div>
                    <div class="team-name"><?php echo $nombre; ?></div>
                    <div class="team-cargo"><?php echo $cargo; ?></div>
                    <div class="team-bio"><?php echo $bio; ?></div>
                    <div class="team-foot">
                        <span class="badge-pill <?php echo $activo ? 'bp-green' : 'bp-gray'; ?>"><?php echo $activo ? 'Activo' : 'Inactivo'; ?></span>
                        <div class="d-flex gap-1">
                            <?php if ($linkedin): ?>
                            <a href="<?php echo $linkedin; ?>" target="_blank" class="btn-icon view" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <?php endif; ?>
                            <button class="btn-icon edit" data-modal-open="modal-miembro"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Modal Miembro -->
<div class="modal-backdrop-custom" id="modal-miembro">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-head">
            <h6>Miembro del equipo</h6>
            <button class="modal-close" data-modal-close="modal-miembro"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="f-label">Nombre completo <span class="text-danger">*</span></label>
                    <input class="f-input" type="text" placeholder="Ej: Juan Carlos Eworo">
                </div>
                <div class="col-md-4">
                    <label class="f-label">Iniciales</label>
                    <input class="f-input" type="text" maxlength="3" placeholder="JC">
                </div>
                <div class="col-12">
                    <label class="f-label">Cargo</label>
                    <input class="f-input" type="text" placeholder="Ej: Director de Operaciones">
                </div>
                <div class="col-12">
                    <label class="f-label">Biografía</label>
                    <textarea class="f-textarea" placeholder="Experiencia y especialización del miembro..."></textarea>
                </div>
                <div class="col-12">
                    <label class="f-label">Foto (ruta o URL)</label>
                    <input class="f-input" type="text" placeholder="/img/equipo/juan-carlos.jpg">
                </div>
                <div class="col-12">
                    <label class="f-label"><i class="fab fa-linkedin me-1" style="color:#0a66c2"></i>LinkedIn</label>
                    <input class="f-input" type="url" placeholder="https://linkedin.com/in/...">
                </div>
                <div class="col-md-4">
                    <label class="f-label">Orden</label>
                    <input class="f-input" type="number" min="0" max="99" placeholder="0">
                </div>
                <div class="col-md-8 d-flex align-items-center justify-content-end gap-3 mt-2">
                    <label class="f-label mb-0">Activo</label>
                    <label class="toggle-sw"><input type="checkbox" checked><span class="toggle-slider"></span></label>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-miembro">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/equipo/equipo.js"></script>
</body></html>
