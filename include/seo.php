<?php
// ─── Etiquetas SEO comunes: URL canónica y Open Graph ───
// Incluir dentro de <head>, después de <title> y <meta name="description">.
// Opcional antes del include: $seoTitulo, $seoDescripcion, $seoImagen (URL absoluta).
if (!defined('RUTA_BASE')) require_once __DIR__ . '/../config/rutas_web.php';

// En producción la URL canónica siempre es https://egidra.com (sin www ni IP)
$_seoBase = ENTORNO === 'produccion' ? 'https://egidra.com/' : RUTA_BASE;
$_seoRuta = parse_url(RUTA_BASE, PHP_URL_PATH) ?: '/';
$_seoPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$_seoRel  = ltrim(str_starts_with($_seoPath, $_seoRuta) ? substr($_seoPath, strlen($_seoRuta)) : $_seoPath, '/');
$_seoRel  = preg_replace('#(^|/)index\.php$#', '$1', $_seoRel);
// Único parámetro que identifica contenido distinto: el id del proyecto
if (isset($_GET['id']) && (int)$_GET['id'] > 0) $_seoRel .= '?id=' . (int)$_GET['id'];
$_seoUrl = $_seoBase . $_seoRel;

$_seoTitulo = $seoTitulo      ?? 'EGIDRA - Buceo Industrial y Acceso por Cuerda de Guinea Ecuatorial';
$_seoDesc   = $seoDescripcion ?? 'Buceo industrial, acceso por cuerda, levantamientos hidrográficos y apoyo logístico para el sector del petróleo y el gas en Guinea Ecuatorial.';
$_seoImagen = $seoImagen      ?? $_seoBase . 'img/empresa/plataforma-offshore.jpg';
// Algunas páginas pasan valores ya escapados: normalizar antes de escapar
$_e = fn($v) => htmlspecialchars(html_entity_decode($v, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
?>
    <link rel="canonical" href="<?php echo $_e($_seoUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="EGIDRA">
    <meta property="og:locale" content="es_ES">
    <meta property="og:url" content="<?php echo $_e($_seoUrl); ?>">
    <meta property="og:title" content="<?php echo $_e($_seoTitulo); ?>">
    <meta property="og:description" content="<?php echo $_e($_seoDesc); ?>">
    <meta property="og:image" content="<?php echo $_e($_seoImagen); ?>">
    <meta name="twitter:card" content="summary_large_image">
<?php unset($_seoBase, $_seoRuta, $_seoPath, $_seoRel, $_seoUrl, $_seoTitulo, $_seoDesc, $_seoImagen, $_e);
