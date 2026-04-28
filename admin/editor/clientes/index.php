<?php
require_once '../../../config/rutas.php';
$pageTitle = 'Clientes'; $pageBreadcrumb = 'Clientes';
$clientes = [
    ['MARATHON','MA','av-y',true],['CHEVRON','CH','av-b',true],['TRIDENT','TR','av-g',true],
    ['REPSOL','RE','av-p',true], ['CEPSA','CE','av-y',true],  ['BP','BP','av-b',true],
];
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
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-client" placeholder="Buscar cliente..."></div>
            <button class="btn-pri" data-modal-open="modal-add-client"><i class="fas fa-plus"></i>Nuevo cliente</button>
        </div>
        <div class="row g-3">
            <?php foreach ($clientes as [$name,$ini,$av,$activo]): ?>
            <div class="col-6 col-md-4 col-lg-3 client-card-wrap">
                <div class="client-card">
                    <div class="client-logo-box <?php echo $av; ?>"><?php echo $ini; ?></div>
                    <div class="client-name"><?php echo $name; ?></div>
                    <div class="client-foot">
                        <span class="badge-pill <?php echo $activo?'bp-green':'bp-gray'; ?>"><?php echo $activo?'Activo':'Inactivo'; ?></span>
                        <div class="d-flex gap-1">
                            <button class="btn-icon edit" data-modal-open="modal-add-client"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-add-client">
    <div class="modal-box">
        <div class="modal-head"><h6>Cliente</h6><button class="modal-close" data-modal-close="modal-add-client"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="f-label">Nombre <span class="text-danger">*</span></label><input class="f-input" type="text" placeholder="Ej: Marathon Oil"></div>
            <div class="mb-3"><label class="f-label">Descripción</label><textarea class="f-textarea" placeholder="Sector, relación comercial..."></textarea></div>
            <div class="mb-0 d-flex align-items-center justify-content-between"><label class="f-label mb-0">Activo</label><label class="toggle-sw"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-add-client">Cancelar</button><button class="btn-pri"><i class="fas fa-check me-1"></i>Guardar</button></div>
    </div>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/clientes/clientes.js"></script>
</body></html>
