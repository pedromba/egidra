<?php
require_once '../include/auth.php';
require_once '../../../config/rutas.php';
$pageTitle = $pageBreadcrumb = 'Mensajes';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Mensajes — EGIDRA Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/base/base.css">
    <link rel="stylesheet" href="../recursos/css/base/components.css">
    <link rel="stylesheet" href="../recursos/css/mensajes/mensajes.css">
</head>
<body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci" style="padding:0;">
        <div class="card-admin" style="border-radius:0;border-left:none;border-right:none;border-bottom:none;">
            <div class="msg-layout">

                <!-- Lista de mensajes — rellenada por mensajes.js -->
                <div class="msg-list" id="msg-list">
                    <div class="msg-loading">
                        <i class="fas fa-spinner fa-spin"></i> Cargando mensajes...
                    </div>
                </div>

                <!-- Panel de detalle -->
                <div class="msg-detail" id="msg-detail">
                    <!-- Estado vacío inicial -->
                    <div class="msg-detail-empty" id="msg-empty">
                        <div class="empty-state">
                            <i class="fas fa-envelope-open"></i>
                            <h6>Seleccione un mensaje</h6>
                            <p>Haga clic en un mensaje de la lista para leerlo.</p>
                        </div>
                    </div>

                    <!-- Contenido del mensaje seleccionado — rellenado por mensajes.js -->
                    <div id="msg-content" style="display:none;flex-direction:column;flex:1;">
                        <div class="msg-detail-head">
                            <h5 id="msg-asunto"></h5>
                            <div class="msg-detail-meta" id="msg-meta"></div>
                        </div>
                        <div class="msg-detail-body" id="msg-cuerpo"></div>
                        <div style="padding:14px 22px;border-top:1px solid var(--border);display:flex;gap:8px;">
                            <button class="btn-pri" id="btn-responder" style="font-size:.78rem;padding:7px 14px;">
                                <i class="fas fa-reply me-1"></i>Responder
                            </button>
                            <button class="btn-sec" id="btn-eliminar" style="font-size:.78rem;">
                                <i class="fas fa-trash me-1"></i>Eliminar
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
<script src="../recursos/js/app/app.js"></script>
<script src="../recursos/js/mensajes/mensajes.js"></script>
</body>
</html>
