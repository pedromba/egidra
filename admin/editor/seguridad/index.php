<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Seguridad HSE'; $pageBreadcrumb = 'Seguridad HSE';
$stats_hse = [
    ['fa-clock',        '500+', 'Horas acumuladas sin incidente grave'],
    ['fa-circle-check', '0',    'Accidentes mortales registrados'],
    ['fa-percent',      '100%', 'Proyectos con EPP verificado'],
    ['fa-users-gear',   '12',   'Simulacros de emergencia al año'],
];
$reglas = [
    ['fa-person-falling-burst','Trabajo en Altura',        'Todo trabajo por encima de 1,8 m requiere arnés homologado, punto de anclaje certificado y permiso firmado.'],
    ['fa-door-closed',          'Espacios Confinados',      'Requiere análisis de atmósfera, vigía externo, equipo de rescate preparado y permiso de entrada específico.'],
    ['fa-bolt',                 'Aislamiento de Energía',   'Procedimiento LOTO obligatorio antes de intervenir cualquier equipo. Solo retira el candado quien lo instaló.'],
    ['fa-fire',                 'Trabajo en Caliente',      'Soldadura y corte requieren zona libre de inflamables, extintor próximo, vigía de incendios y permiso.'],
    ['fa-biohazard',            'Sustancias Peligrosas',    'Consultar ficha SDS, usar EPP específico y disponer de kit de derrames antes de manipular químicos.'],
    ['fa-hard-hat',             'EPP Obligatorio',          'Casco, gafas, guantes y calzado de seguridad son obligatorios en toda zona de trabajo activa.'],
    ['fa-car',                  'Conducción Segura',        'Cinturón obligatorio, velocidad máxima por zona, prohibición total de móvil al volante.'],
    ['fa-shovel',               'Control de Excavaciones',  'Verificar servicios enterrados antes de excavar. Entibación en zanjas >1,2 m. Señalización perimetral.'],
    ['fa-gears',                'Integridad Mecánica',      'Ningún equipo en mal estado opera. Inspección pre-uso obligatoria. Deficiencias reportadas de inmediato.'],
];
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Seguridad HSE — EGIDRA Editor</title>
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
            <?php foreach ($reglas as $i => [$icon,$title,$desc]): ?>
            <div class="rule-row">
                <div class="rule-num"><?php echo $i+1; ?></div>
                <div class="rule-icon-box"><i class="fas <?php echo $icon; ?>"></i></div>
                <div style="flex:1;min-width:0;">
                    <div class="rule-title"><?php echo $title; ?></div>
                    <div class="rule-desc"><?php echo $desc; ?></div>
                </div>
                <div class="rule-actions">
                    <button class="btn-icon edit" data-modal-open="modal-edit-rule"><i class="fas fa-pen"></i></button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Cifras HSE -->
        <div class="card-admin mt-3">
            <div class="card-head">
                <h6><i class="fas fa-chart-simple me-2" style="color:var(--primary)"></i>Cifras HSE</h6>
                <button class="btn-pri" style="font-size:.75rem;padding:6px 12px;" data-modal-open="modal-stat">
                    <i class="fas fa-plus"></i>Nueva cifra
                </button>
            </div>
            <div class="row g-0">
                <?php foreach ($stats_hse as $i => [$icon, $valor, $etiqueta]): ?>
                <div class="col-6 col-md-3 stat-hse-cell<?php echo $i > 0 ? ' stat-hse-border' : ''; ?>">
                    <div class="stat-hse-icon"><i class="fas <?php echo $icon; ?>"></i></div>
                    <div class="stat-hse-val"><?php echo $valor; ?></div>
                    <div class="stat-hse-label"><?php echo $etiqueta; ?></div>
                    <div class="stat-hse-actions">
                        <button class="btn-icon edit" data-modal-open="modal-stat"><i class="fas fa-pen"></i></button>
                        <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
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
            <div class="mb-3"><label class="f-label">Título</label><input class="f-input" type="text" value="Trabajo en Altura"></div>
            <div class="mb-3"><label class="f-label">Icono (clase FA)</label><input class="f-input" type="text" value="fa-person-falling-burst"></div>
            <div class="mb-0"><label class="f-label">Descripción</label><textarea class="f-textarea">Todo trabajo por encima de 1,8 m requiere arnés homologado, punto de anclaje certificado y permiso firmado.</textarea></div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-edit-rule">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<!-- Modal Cifra HSE -->
<div class="modal-backdrop-custom" id="modal-stat">
    <div class="modal-box">
        <div class="modal-head">
            <h6>Cifra HSE</h6>
            <button class="modal-close" data-modal-close="modal-stat"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="f-label">Valor <span class="text-danger">*</span></label>
                <input class="f-input" type="text" placeholder="Ej: 500+, 0, 100%">
            </div>
            <div class="mb-3">
                <label class="f-label">Etiqueta <span class="text-danger">*</span></label>
                <input class="f-input" type="text" placeholder="Descripción de la cifra">
            </div>
            <div class="mb-3">
                <label class="f-label">Icono (clase FA)</label>
                <input class="f-input" type="text" placeholder="fa-clock">
            </div>
            <div class="mb-0">
                <label class="f-label">Orden</label>
                <input class="f-input" type="number" min="0" max="99" placeholder="0">
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-stat">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/seguridad/seguridad.js"></script>
</body></html>
