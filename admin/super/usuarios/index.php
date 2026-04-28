<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = 'Usuarios'; $pageBreadcrumb = 'Usuarios';
?>
<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Usuarios — EGIDRA Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../recursos/css/base/base.css">
<link rel="stylesheet" href="../recursos/css/base/components.css">
<link rel="stylesheet" href="../recursos/css/usuarios/usuarios.css">
</head><body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
            <div class="d-flex gap-2 flex-wrap">
                <div class="f-search" style="width:240px;"><i class="fas fa-search"></i><input type="text" id="search-user" placeholder="Buscar usuario..."></div>
                <button class="btn-sec active" data-role-filter="">Todos</button>
                <button class="btn-sec" data-role-filter="Super">Super</button>
                <button class="btn-sec" data-role-filter="Editor">Editor</button>
            </div>
            <button class="btn-pri" id="btn-nuevo-usuario"><i class="fas fa-user-plus"></i>Nuevo usuario</button>
        </div>
        <div class="card-admin">
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead><tr><th>Usuario</th><th>Rol</th><th>Estado</th><th>Último acceso</th><th>Creado</th><th></th></tr></thead>
                    <tbody id="tbody-usuarios">
                        <tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<div class="modal-backdrop-custom" id="modal-usuario">
    <div class="modal-box">
        <div class="modal-head"><h6 id="modal-usuario-title">Nuevo Usuario</h6><button class="modal-close" data-modal-close="modal-usuario"><i class="fas fa-times"></i></button></div>
        <div class="modal-body">
            <input type="hidden" id="usr-id">
            <div class="mb-3"><label class="f-label">Nombre <span class="text-danger">*</span></label><input class="f-input" id="usr-nombre" type="text" placeholder="Nombre completo"></div>
            <div class="mb-3"><label class="f-label">Email <span class="text-danger">*</span></label><input class="f-input" id="usr-email" type="email" placeholder="correo@egidra.com"></div>
            <div class="mb-3"><label class="f-label">Contraseña <span id="pass-hint" class="text-muted" style="font-size:.75rem;">(dejar vacío para no cambiar)</span></label><input class="f-input" id="usr-pass" type="password" placeholder="••••••••"></div>
            <div class="mb-3"><label class="f-label">Rol</label><select class="f-select" id="usr-rol"><option value="Editor">Editor</option><option value="Super">Super</option></select></div>
            <div class="d-flex align-items-center justify-content-between"><label class="f-label mb-0">Estado activo</label><label class="toggle-sw"><input type="checkbox" id="usr-activo" checked><span class="toggle-slider"></span></label></div>
        </div>
        <div class="modal-foot"><button class="btn-sec" data-modal-close="modal-usuario">Cancelar</button><button class="btn-pri" id="btn-guardar-usuario"><i class="fas fa-check me-1"></i>Guardar</button></div>
    </div>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/usuarios/usuarios.js"></script>
</body></html>
