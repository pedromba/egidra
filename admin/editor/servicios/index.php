<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Servicios';
$pageBreadcrumb = 'Servicios';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Servicios — EGIDRA Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/base/base.css">
    <link rel="stylesheet" href="../recursos/css/base/components.css">
    <link rel="stylesheet" href="../recursos/css/servicios/servicios.css">
</head>

<body>
    <?php include '../include/aside.php'; ?>
    <div class="cw">
        <?php include '../include/header.php'; ?>
        <main class="ci">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <p class="mb-0" id="stats-servicios" style="font-size:.82rem;color:var(--muted);">Cargando...</p>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="svc-tabs">
                        <button class="svc-tab activo" id="btn-tab-svc"><i class="fas fa-list me-1"></i>Servicios</button>
                        <button class="svc-tab" id="btn-tab-cat"><i class="fas fa-folder me-1"></i>Categorías</button>
                    </div>
                    <button class="btn-sec" id="btn-nueva-cat" style="display:none;"><i class="fas fa-folder-plus"></i>Nueva categoría</button>
                    <button class="btn-pri" id="btn-nuevo"><i class="fas fa-plus"></i>Nuevo servicio</button>
                </div>
            </div>
            <div id="grid-servicios">
                <div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>
            </div>
        </main>
    </div>

    <!-- Modal Ver Servicio -->
    <div class="modal-backdrop-custom" id="modal-svc-info">
        <div class="modal-box">
            <div class="modal-head">
                <h6>Detalles del servicio</h6><button class="modal-close" data-modal-close="modal-svc-info"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="svc-info-header mb-4">
                    <div class="svc-info-icon" id="info-icono-preview"><i class="fas fa-cog"></i></div>
                    <div>
                        <h5 id="info-titulo" class="mb-1" style="font-size:.95rem;font-weight:700;"></h5>
                        <span id="info-categoria" class="svc-cat-badge"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="f-label">Descripción</label>
                    <p id="info-desc" class="svc-info-text"></p>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="f-label">Icono (clase FA)</label>
                        <p id="info-icono-clase" class="svc-info-text"></p>
                    </div>
                    <div class="col-6">
                        <label class="f-label">Orden</label>
                        <p id="info-orden" class="svc-info-text"></p>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    <div><label class="f-label">Destacado</label><div id="info-destacado"></div></div>
                    <div><label class="f-label">Estado</label><div id="info-activo"></div></div>
                </div>
            </div>
            <div class="modal-foot">
                <button class="btn-sec" data-modal-close="modal-svc-info">Cerrar</button>
                <button class="btn-pri" id="btn-editar-desde-info"><i class="fas fa-pen me-1"></i>Editar</button>
            </div>
        </div>
    </div>

    <!-- Modal Servicio -->
    <div class="modal-backdrop-custom" id="modal-svc">
        <div class="modal-box">
            <div class="modal-head">
                <h6 id="modal-svc-title">Nuevo Servicio</h6><button class="modal-close" data-modal-close="modal-svc"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="svc-id">
                <div class="mb-3"><label class="f-label">Categoría <span class="text-danger">*</span></label><select class="f-select" id="svc-cat">
                        <option value="">— Seleccionar —</option>
                    </select></div>
                <div class="mb-3"><label class="f-label">Título <span class="text-danger">*</span></label><input class="f-input" id="svc-titulo" type="text" placeholder="Ej: Inspección ROV"></div>
                <div class="mb-3"><label class="f-label">Descripción</label><textarea class="f-textarea" id="svc-desc" placeholder="Descripción detallada..."></textarea></div>
                <div class="mb-3"><label class="f-label">Icono (clase FA)</label><input class="f-input" id="svc-icono" type="text" placeholder="fa-water"></div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="f-label">Orden</label><input class="f-input" id="svc-orden" type="number" min="0" max="99" placeholder="0"></div>
                </div>
                <div class="d-flex gap-4">
                    <div class="d-flex align-items-center justify-content-between flex-grow-1"><label class="f-label mb-0">Destacado</label><label class="toggle-sw"><input type="checkbox" id="svc-dest"><span class="toggle-slider"></span></label></div>
                    <div class="d-flex align-items-center justify-content-between flex-grow-1"><label class="f-label mb-0">Activo</label><label class="toggle-sw"><input type="checkbox" id="svc-activo" checked><span class="toggle-slider"></span></label></div>
                </div>
            </div>
            <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-svc">Cancelar</button><button class="btn-pri" id="btn-guardar-svc"><i class="fas fa-check me-1"></i>Guardar</button></div>
        </div>
    </div>

    <!-- Modal Categoría -->
    <div class="modal-backdrop-custom" id="modal-cat">
        <div class="modal-box">
            <div class="modal-head">
                <h6 id="modal-cat-title">Nueva Categoría</h6><button class="modal-close" data-modal-close="modal-cat"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cat-id">
                <div class="mb-3"><label class="f-label">Nombre <span class="text-danger">*</span></label><input class="f-input" id="cat-nombre" type="text" placeholder="Ej: Buceo &amp; Subsea"></div>
                <div class="mb-3"><label class="f-label">Descripción</label><textarea class="f-textarea" id="cat-desc" placeholder="Descripción breve..."></textarea></div>
                <div class="mb-3"><label class="f-label">Icono (clase FA)</label><input class="f-input" id="cat-icono" type="text" placeholder="fa-water"></div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="f-label">Orden</label><input class="f-input" id="cat-orden" type="number" min="0" max="99" placeholder="0"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <label class="f-label mb-0">Activo</label>
                    <label class="toggle-sw"><input type="checkbox" id="cat-activo" checked><span class="toggle-slider"></span></label>
                </div>
            </div>
            <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-cat">Cancelar</button><button class="btn-pri" id="btn-guardar-cat"><i class="fas fa-check me-1"></i>Guardar</button></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="../recursos/js/app/app.js"></script>
    <script src="../recursos/js/servicios/servicios.js"></script>
</body>

</html>