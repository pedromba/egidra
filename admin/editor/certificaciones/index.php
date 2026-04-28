<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Certificaciones'; $pageBreadcrumb = 'Certificaciones';

$certs = [
    ['Diving Contractor Membership',  'IMCA',           2018, null,         true, 'vigente'],
    ['Rope Access Contractor',        'IRATA',           2019, '2025-12-31', true, 'vigente'],
    ['ISO 9001:2015 — Calidad',       'Bureau Veritas',  2020, '2026-03-31', true, 'vigente'],
    ['ISO 45001:2018 — SST',          'Bureau Veritas',  2021, '2027-03-31', true, 'vigente'],
    ['Offshore Safety Certificate',   'DNV GL',          2022, '2024-06-30', true, 'vencida'],
];

function certBadge(string $estado): string {
    return match($estado) {
        'vigente' => '<span class="badge-pill cert-vigente">Vigente</span>',
        'vence'   => '<span class="badge-pill cert-vence">Por vencer</span>',
        default   => '<span class="badge-pill cert-vencida">Vencida</span>',
    };
}
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Certificaciones — EGIDRA Editor</title>
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
            <button class="btn-pri" data-modal-open="modal-cert"><i class="fas fa-plus"></i>Nueva certificación</button>
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
                    <tbody>
                    <?php foreach ($certs as [$nombre, $org, $anio, $vence, $activo, $estado]): ?>
                    <tr>
                        <td>
                            <div class="cert-name"><?php echo $nombre; ?></div>
                            <div class="cert-active"><?php echo $activo ? '<span class="badge-pill bp-green">Publicada</span>' : '<span class="badge-pill bp-gray">Oculta</span>'; ?></div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="org-badge"><?php echo strtoupper(substr($org, 0, 2)); ?></div>
                                <span><?php echo $org; ?></span>
                            </div>
                        </td>
                        <td><?php echo $anio; ?></td>
                        <td style="color:var(--muted);font-size:.8rem;"><?php echo $vence ? date('d/m/Y', strtotime($vence)) : '<span style="color:var(--muted)">Indefinida</span>'; ?></td>
                        <td><?php echo certBadge($estado); ?></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn-icon edit" data-modal-open="modal-cert"><i class="fas fa-pen"></i></button>
                                <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Modal Certificación -->
<div class="modal-backdrop-custom" id="modal-cert">
    <div class="modal-box" style="max-width:540px;">
        <div class="modal-head">
            <h6>Certificación</h6>
            <button class="modal-close" data-modal-close="modal-cert"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="f-label">Nombre <span class="text-danger">*</span></label>
                    <input class="f-input" type="text" placeholder="Ej: ISO 9001:2015 — Calidad">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Organismo emisor</label>
                    <input class="f-input" type="text" placeholder="Ej: Bureau Veritas">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Año de obtención</label>
                    <input class="f-input" type="number" min="1990" max="2099" placeholder="2024">
                </div>
                <div class="col-12">
                    <label class="f-label">Descripción</label>
                    <textarea class="f-textarea" placeholder="Alcance y descripción de la certificación..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="f-label">Logo (ruta)</label>
                    <input class="f-input" type="text" placeholder="/img/certs/iso9001.svg">
                </div>
                <div class="col-md-6">
                    <label class="f-label">URL de verificación</label>
                    <input class="f-input" type="url" placeholder="https://...">
                </div>
                <div class="col-md-6">
                    <label class="f-label">Fecha de vencimiento</label>
                    <input class="f-input" type="date">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-end gap-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="f-label mb-0">Publicada</label>
                        <label class="toggle-sw"><input type="checkbox" checked><span class="toggle-slider"></span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button class="btn-sec" data-modal-close="modal-cert">Cancelar</button>
            <button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button>
        </div>
    </div>
</div>

<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/certificaciones/certificaciones.js"></script>
</body></html>
