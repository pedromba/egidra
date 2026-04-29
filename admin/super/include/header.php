<?php
if (!defined('PROJECT_ROOT')) {
    require_once __DIR__ . '/../../../config/init.php';
}
$pageTitle      ??= 'Dashboard';
$pageBreadcrumb ??= 'Panel de Control';
?>
<header class="topbar">
    <div class="tb-left">
        <button class="tb-toggle" id="tb-toggle"><i class="fas fa-bars"></i></button>
        <div>
            <div class="tb-page-title"><?php echo htmlspecialchars($pageTitle); ?></div>
            <div class="tb-breadcrumb">Admin &rsaquo; <?php echo htmlspecialchars($pageBreadcrumb); ?></div>
        </div>
    </div>
    <div class="tb-right">
        <a href="<?php echo RUTA_ADMIN_SUPER; ?>mensajes/" class="tb-btn" title="Mensajes">
            <i class="fas fa-envelope"></i><span class="tb-dot"></span>
        </a>
        <a href="<?php echo RUTA_ADMIN_SUPER; ?>logs/" class="tb-btn" title="Logs">
            <i class="fas fa-bell"></i>
        </a>

        <!-- User dropdown -->
        <div class="tb-user-wrap" id="tb-user-wrap">
            <button class="tb-user-btn" id="tb-user-btn" type="button" aria-haspopup="true" aria-expanded="false">
                <div class="tb-avatar"><i class="fas fa-user-shield"></i></div>
                <span class="tb-user-name"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Super Admin'); ?></span>
                <i class="fas fa-chevron-down tb-chevron"></i>
            </button>
            <div class="tb-dropdown" id="tb-dropdown" role="menu">
                <div class="tb-dd-info">
                    <div class="tb-dd-name"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Super Admin'); ?></div>
                    <div class="tb-dd-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
                </div>
                <div class="tb-dd-sep"></div>
                <a href="<?php echo RUTA_ADMIN_SUPER; ?>perfil/" class="tb-dd-item" role="menuitem">
                    <i class="fas fa-user-pen"></i>Mi perfil
                </a>
                <div class="tb-dd-sep"></div>
                <button type="button" class="tb-dd-item tb-dd-danger btn-logout" data-logout-url="<?php echo RUTA_ADMIN; ?>api/logout.php" role="menuitem" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;">
                    <i class="fas fa-right-from-bracket"></i>Cerrar sesión
                </button>
            </div>
        </div>

    </div>
</header>
