<?php
// ─── Sitemap XML dinámico ───
// Servido como /sitemap.xml (ver .htaccess). Incluye las páginas públicas
// y cada proyecto activo, para que los nuevos proyectos del admin se indexen solos.
require_once __DIR__ . '/config/rutas.php';
require_once __DIR__ . '/config/conexion.php';

header('Content-Type: application/xml; charset=utf-8');

// ─── Fecha de última modificación de un archivo PHP (formato W3C) ───
function lastmod_archivo(string $ruta): string {
    return date('Y-m-d', @filemtime(DIR_BASE . $ruta) ?: time());
}

// ─── Páginas estáticas: [ruta, archivo, frecuencia, prioridad] ───
$paginas = [
    ['',                'index.php',                'weekly',  '1.0'],
    ['servicios/',      'servicios/index.php',      'monthly', '0.9'],
    ['proyectos/',      'proyectos/index.php',      'weekly',  '0.9'],
    ['sobre-nosotros/', 'sobre-nosotros/index.php', 'monthly', '0.8'],
    ['seguridad/',      'seguridad/index.php',      'monthly', '0.8'],
    ['socios/',         'socios/index.php',         'monthly', '0.6'],
    ['contacto/',       'contacto/index.php',       'yearly',  '0.7'],
];

// El contenido de estas páginas sale de la BD: usar la fecha de actualización de empresa si es más reciente
$emp = $conexion->query("SELECT fecha_actualizacion FROM empresa WHERE id = 1 LIMIT 1")->fetch_assoc();
$fechaEmpresa = !empty($emp['fecha_actualizacion']) ? date('Y-m-d', strtotime($emp['fecha_actualizacion'])) : null;

$urls = [];
foreach ($paginas as [$ruta, $archivo, $freq, $prio]) {
    $lastmod = lastmod_archivo($archivo);
    if ($fechaEmpresa && $fechaEmpresa > $lastmod) $lastmod = $fechaEmpresa;
    $urls[] = ['loc' => RUTA_BASE . $ruta, 'lastmod' => $lastmod, 'freq' => $freq, 'prio' => $prio];
}

// ─── Proyectos activos ───
$res = $conexion->query("SELECT id_proyecto FROM proyectos WHERE activo = 1 ORDER BY id_proyecto ASC");
$lastmodProyecto = lastmod_archivo('proyectos/verProyecto.php');
while ($row = $res->fetch_assoc()) {
    $urls[] = [
        'loc'     => RUTA_BASE . 'proyectos/verProyecto.php?id=' . (int)$row['id_proyecto'],
        'lastmod' => $lastmodProyecto,
        'freq'    => 'monthly',
        'prio'    => '0.6',
    ];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
    echo "    <lastmod>{$u['lastmod']}</lastmod>\n";
    echo "    <changefreq>{$u['freq']}</changefreq>\n";
    echo "    <priority>{$u['prio']}</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
