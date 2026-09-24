<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    $dest = ['super' => './super/', 'editor' => './editor/'][$_SESSION['rol'] ?? ''] ?? null;
    if ($dest) { header('Location: ' . $dest); exit; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGIDRA — Acceso LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="recursos/css/login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <!-- Brand -->
        <div class="login-brand">
            <div class="brand-icon">
                <i class="fas fa-anchor"></i>
            </div>
            <h1>EGIDRA</h1>
            <p>Panel de Administración</p>
        </div>

        <!-- Form -->
        <form id="login-form" action="./api/login.php" novalidate>

            <div>
                <label class="form-label">Correo electrónico</label>
                <div class="input-group-admin">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="email-input" class="form-control-admin"
                           placeholder="email@egidra.com" required autocomplete="email">
                </div>
            </div>

            <div>
                <label class="form-label">Contraseña</label>
                <div class="input-group-admin">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="contrasena" id="pass-input" class="form-control-admin"
                           placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="toggle-pass" id="toggle-pass" aria-label="Mostrar contraseña">
                        <i class="fas fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" id="remember-me">
                    <span>Recordarme</span>
                </label>
            </div>

            <button type="submit" class="btn-login" id="btn-login">
                <i class="fas fa-right-to-bracket me-2"></i>Iniciar Sesión
            </button>
        </form>

        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> EGIDRA &mdash; Acceso restringido al personal autorizado
        </div>
    </div>
</div>

<script src="recursos/js/login.js"></script>
</body>
</html>
