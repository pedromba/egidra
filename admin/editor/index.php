<?php
require_once 'include/auth.php';
require_once '../../config/rutas.php';
$pageTitle = $pageBreadcrumb = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — EGIDRA Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="recursos/css/base/base.css">
    <link rel="stylesheet" href="recursos/css/base/components.css">
    <link rel="stylesheet" href="recursos/css/dashboard/dashboard.css">
</head>
<body>
<?php include 'include/aside.php'; ?>
<div class="cw">
    <?php include 'include/header.php'; ?>
    <main class="ci">

        <!-- Welcome -->
        <div class="welcome-bar">
            <div class="welcome-text">
                <h5>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Editor'); ?></h5>
                <p><?php echo date('l, d \d\e F \d\e Y'); ?> &mdash; Panel de edición EGIDRA</p>
            </div>
            <a href="<?php echo RUTA_BASE; ?>" target="_blank" class="btn-pri">
                <i class="fas fa-arrow-up-right-from-square"></i>Ver sitio web
            </a>
        </div>

        <!-- KPIs — valores rellenados por dashboard.js -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="kpi-icon y"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="kpi-val" id="kpi-mensajes">—</div>
                        <div class="kpi-label">Mensajes nuevos</div>
                        <div class="kpi-trend neu" id="kpi-mensajes-trend"><i class="fas fa-minus me-1"></i>Cargando...</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="kpi-icon b"><i class="fas fa-folder-open"></i></div>
                    <div>
                        <div class="kpi-val" id="kpi-proyectos">—</div>
                        <div class="kpi-label">Proyectos</div>
                        <div class="kpi-trend neu"><i class="fas fa-minus me-1"></i>Sin cambios</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="kpi-icon g"><i class="fas fa-building"></i></div>
                    <div>
                        <div class="kpi-val" id="kpi-clientes">—</div>
                        <div class="kpi-label">Clientes</div>
                        <div class="kpi-trend neu"><i class="fas fa-minus me-1"></i>Sin cambios</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="kpi-icon p"><i class="fas fa-hard-hat"></i></div>
                    <div>
                        <div class="kpi-val" id="kpi-servicios">—</div>
                        <div class="kpi-label">Servicios activos</div>
                        <div class="kpi-trend neu"><i class="fas fa-minus me-1"></i>Sin cambios</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle row -->
        <div class="row g-3">

            <!-- Acciones rápidas -->
            <div class="col-lg-4">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-bolt me-2" style="color:var(--primary)"></i>Acciones rápidas</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="qa-grid">
                            <a href="servicios/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-hard-hat"></i></div>Servicios
                            </a>
                            <a href="proyectos/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-folder-plus"></i></div>Proyectos
                            </a>
                            <a href="clientes/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-building"></i></div>Clientes
                            </a>
                            <a href="mensajes/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-envelope-open"></i></div>Mensajes
                            </a>
                            <a href="seguridad/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-shield-halved"></i></div>HSE
                            </a>
                            <a href="equipo/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-users-gear"></i></div>Equipo
                            </a>
                            <a href="valores/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-star"></i></div>Valores
                            </a>
                            <a href="socios/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-handshake"></i></div>Socios
                            </a>
                            <a href="certificaciones/" class="qa-item">
                                <div class="qa-ico"><i class="fas fa-certificate"></i></div>Certif.
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes recientes — tbody rellenado por dashboard.js -->
            <div class="col-lg-8">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-envelope me-2" style="color:var(--primary)"></i>Mensajes recientes</h6>
                        <a href="mensajes/" class="btn-sec" style="font-size:.72rem;padding:5px 10px;">Ver todos</a>
                    </div>
                    <div class="tbl-wrap">
                        <table class="tbl">
                            <thead><tr>
                                <th>Remitente</th>
                                <th>Asunto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr></thead>
                            <tbody id="tbody-mensajes">
                                <tr><td colspan="5" class="text-center text-muted py-3">Cargando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
<script src="recursos/js/app/app.js"></script>
<script src="recursos/js/dashboard/dashboard.js"></script>
</body>
</html>
