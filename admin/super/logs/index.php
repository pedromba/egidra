<?php
require_once '../include/auth.php';
require_once '../../../config/init.php';
$pageTitle = 'Logs de Actividad'; $pageBreadcrumb = 'Logs';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Logs — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/logs/logs.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-log" placeholder="Buscar en logs..."></div>
            <button class="btn-sec active" data-log-filter="">Todos</button>
            <button class="btn-sec" data-log-filter="LOGIN">Login</button>
            <button class="btn-sec" data-log-filter="CREAR">Crear</button>
            <button class="btn-sec" data-log-filter="EDITAR">Editar</button>
            <button class="btn-sec" data-log-filter="ELIMINAR">Eliminar</button>
            <button class="btn-sec" data-log-filter="SISTEMA">Sistema</button>
        </div>
        <div class="card-admin">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead><tr><th>Acción</th><th>Usuario</th><th>Tabla</th><th>Detalle</th><th>IP</th><th>Fecha</th><th style="width:48px"></th></tr></thead>
                    <tbody id="tbody-logs">
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<!-- Modal detalle log -->
<div class="modal fade" id="modal-log-detalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header" style="background:var(--primary,#0d6efd);color:#fff;border:none;">
                <h6 class="modal-title fw-bold mb-0"><i class="fas fa-list-check me-2"></i>Detalle del Registro</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-sm mb-0" id="tbl-log-detalle">
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/logs/logs.js"></script>
</body></html>
