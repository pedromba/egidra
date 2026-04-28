<?php
if (!defined('RUTA_BASE')) {
    require_once __DIR__ . '/../../../config/rutas.php';
}
require_once __DIR__ . '/../../../config/conexion.php';

$base = RUTA_BASE . 'admin/super/';
$uri  = $_SERVER['REQUEST_URI'] ?? '';

$section = match(true) {
    str_contains($uri, '/empresa')         => 'empresa',
    str_contains($uri, '/valores')         => 'valores',
    str_contains($uri, '/equipo')          => 'equipo',
    str_contains($uri, '/socios')          => 'socios',
    str_contains($uri, '/certificaciones') => 'certificaciones',
    str_contains($uri, '/usuarios')        => 'usuarios',
    str_contains($uri, '/servicios')       => 'servicios',
    str_contains($uri, '/proyectos')       => 'proyectos',
    str_contains($uri, '/clientes')        => 'clientes',
    str_contains($uri, '/mensajes')        => 'mensajes',
    str_contains($uri, '/seguridad')       => 'seguridad',
    str_contains($uri, '/logs')            => 'logs',
    default                                => 'dashboard',
};

// Obtener datos de empresa con caché en sesión
$empresa = $_SESSION['empresa_data'] ?? null;
if (!$empresa) {
    $row = $conexion->query(
        "SELECT nombre, logo FROM empresa WHERE id = 1 LIMIT 1"
    )->fetch_assoc();
    $empresa = $row ?? ['nombre' => 'EGIDRA', 'logo' => null];
    $_SESSION['empresa_data'] = $empresa;
}

// Obtener número de mensajes sin leer
$mensajes_sin_leer = 0;
$result = $conexion->query("SELECT COUNT(*) as total FROM contacto WHERE leido = FALSE");
if ($result) {
    $row = $result->fetch_assoc();
    $mensajes_sin_leer = (int)($row['total'] ?? 0);
}

function sbLink(string $href, string $icon, string $label, string $key, string $cur, string $badge = ''): void {
    $a = ($key === $cur) ? ' active' : '';
    echo "<a href=\"{$href}\" class=\"sb-link{$a}\">"
       . "<span class=\"sb-link-icon\"><i class=\"fas {$icon}\"></i></span>"
       . "<span class=\"label\">{$label}</span>"
       . ($badge ? "<span class=\"sb-badge\">{$badge}</span>" : '')
       . "</a>";
}
?>
<aside class="sidebar" id="sidebar">

    <a class="sb-brand" href="<?php echo $base; ?>">
        <div class="sb-brand-icon">
            <?php if ($empresa['logo']): ?>
                <img src="<?php echo htmlspecialchars($empresa['logo']); ?>" alt="<?php echo htmlspecialchars($empresa['nombre']); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            <?php else: ?>
                <i class="fas fa-layer-group"></i>
            <?php endif; ?>
        </div>
        <div>
            <div class="sb-brand-name"><?php echo htmlspecialchars($empresa['nombre']); ?></div>
            <div class="sb-brand-sub">Super Admin</div>
        </div>
    </a>

    <nav class="sb-nav">

        <div class="sb-group">Principal</div>
        <?php sbLink($base,                   'fa-gauge-high',    'Dashboard',      'dashboard', $section); ?>
        <?php sbLink($base.'mensajes/',        'fa-envelope',      'Mensajes',       'mensajes',  $section, $mensajes_sin_leer > 0 ? (string)$mensajes_sin_leer : ''); ?>

        <div class="sb-group">Contenido</div>
        <?php sbLink($base.'servicios/',       'fa-hard-hat',      'Servicios',      'servicios', $section); ?>
        <?php sbLink($base.'proyectos/',       'fa-folder-open',   'Proyectos',      'proyectos', $section); ?>
        <?php sbLink($base.'clientes/',        'fa-building',      'Clientes',       'clientes',  $section); ?>
        <?php sbLink($base.'seguridad/',       'fa-shield-halved', 'Seguridad HSE',  'seguridad', $section); ?>

        <div class="sb-group">Corporativo</div>
        <?php sbLink($base.'empresa/',         'fa-building-columns', 'Empresa',        'empresa',         $section); ?>
        <?php sbLink($base.'valores/',         'fa-star',             'Valores',         'valores',         $section); ?>
        <?php sbLink($base.'equipo/',          'fa-users-gear',       'Equipo',          'equipo',          $section); ?>
        <?php sbLink($base.'socios/',          'fa-handshake',        'Socios',          'socios',          $section); ?>
        <?php sbLink($base.'certificaciones/', 'fa-certificate',      'Certificaciones', 'certificaciones', $section); ?>

        <div class="sb-group">Sistema</div>
        <?php sbLink($base.'usuarios/',        'fa-users',         'Usuarios',       'usuarios',  $section); ?>
        <?php sbLink($base.'logs/',            'fa-list-check',    'Logs',           'logs',      $section); ?>

        <div class="sb-group">Web</div>
        <?php sbLink(RUTA_BASE,                'fa-arrow-up-right-from-square', 'Ver Sitio', '', $section); ?>

    </nav>

    <div class="sb-user">
        <div class="sb-avatar"><i class="fas fa-user-shield"></i></div>
        <div>
            <div class="sb-user-name"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Super Admin'); ?></div>
            <div class="sb-user-role">Acceso total</div>
        </div>
        
    </div>

</aside>
<div class="sb-overlay" id="sb-overlay"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
