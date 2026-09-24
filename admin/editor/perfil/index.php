<?php
require_once '../include/auth.php';
require_once '../../../config/init.php';

$pageTitle      = 'Mi Perfil';
$pageBreadcrumb = 'Mi Perfil';

$stmt = $conexion->prepare(
    "SELECT nombre, email, rol, fecha_creacion FROM usuarios WHERE id_usuario = ?"
);
$id = (int)$_SESSION['user_id'];
$stmt->bind_param('i', $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

$iniciales = implode('', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice(explode(' ', trim($usuario['nombre'])), 0, 2)));
$apiUrl    = RUTA_ADMIN . 'api/perfil/guardar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil — EGIDRA Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../recursos/css/base/base.css">
    <link rel="stylesheet" href="../recursos/css/base/components.css">
    <link rel="stylesheet" href="../recursos/css/perfil/perfil.css">
</head>
<body>
<?php include '../include/aside.php'; ?>
<div class="cw">
    <?php include '../include/header.php'; ?>
    <main class="ci">

        <!-- Hero -->
        <div class="perfil-hero">
            <div class="perfil-avatar"><?php echo htmlspecialchars($iniciales); ?></div>
            <div class="perfil-hero-info">
                <h4 id="hero-nombre"><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                <p id="hero-email"><?php echo htmlspecialchars($usuario['email']); ?></p>
                <div class="perfil-badge">
                    <i class="fas fa-user-pen"></i>
                    <?php echo htmlspecialchars($usuario['rol']); ?>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Información personal -->
            <div class="col-lg-6">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-id-card me-2" style="color:var(--primary)"></i>Información personal</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="perfil-section-title">Datos de la cuenta</div>

                        <div class="mb-3">
                            <label class="f-label">Nombre completo <span class="text-danger">*</span></label>
                            <input class="f-input" id="p-nombre" type="text" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="f-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input class="f-input" id="p-email" type="email" disabled value="<?php echo htmlspecialchars($usuario['email']); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="f-label">Rol</label>
                            <input class="f-input" type="text" value="<?php echo htmlspecialchars($usuario['rol']); ?>" disabled style="background:#f9fafb;color:var(--muted);">
                        </div>

                        <div id="info-msg" style="display:none;margin-bottom:12px;border-radius:8px;padding:9px 12px;font-size:.78rem;"></div>

                        <button class="btn-pri" id="btn-guardar-info">
                            <i class="fas fa-check me-1"></i>Guardar cambios
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cambiar contraseña -->
            <div class="col-lg-6">
                <div class="card-admin h-100">
                    <div class="card-head">
                        <h6><i class="fas fa-lock me-2" style="color:var(--primary)"></i>Cambiar contraseña</h6>
                    </div>
                    <div class="card-body-p">
                        <div class="perfil-section-title">Seguridad</div>

                        <div class="mb-3">
                            <label class="f-label">Contraseña actual <span class="text-danger">*</span></label>
                            <input class="f-input" id="p-actual" type="password" placeholder="••••••••" autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label class="f-label">Nueva contraseña <span class="text-danger">*</span></label>
                            <input class="f-input" id="p-nueva" type="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password">
                            <div class="pass-strength"><div class="pass-strength-bar" id="p-bar"></div></div>
                            <div class="pass-strength-label" id="p-bar-lbl"></div>
                        </div>
                        <div class="mb-4">
                            <label class="f-label">Confirmar nueva contraseña <span class="text-danger">*</span></label>
                            <input class="f-input" id="p-confirmar" type="password" placeholder="Repite la contraseña" autocomplete="new-password">
                        </div>

                        <div id="pass-msg" style="display:none;margin-bottom:12px;border-radius:8px;padding:9px 12px;font-size:.78rem;"></div>

                        <button class="btn-pri" id="btn-guardar-pass">
                            <i class="fas fa-key me-1"></i>Actualizar contraseña
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Datos de cuenta -->
        <div class="card-admin mt-4">
            <div class="card-body-p" style="display:flex;gap:32px;flex-wrap:wrap;">
                <div>
                    <div class="perfil-section-title" style="margin-bottom:4px;">Miembro desde</div>
                    <div style="font-size:.85rem;color:var(--text);font-weight:600;">
                        <?php echo date('d \d\e F \d\e Y', strtotime($usuario['fecha_creacion'])); ?>
                    </div>
                </div>
                <div>
                    <div class="perfil-section-title" style="margin-bottom:4px;">Email</div>
                    <div style="font-size:.85rem;color:var(--text);font-weight:600;" id="info-email-summary">
                        <?php echo htmlspecialchars($usuario['email']); ?>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script src="../recursos/js/app/app.js"></script>
<script>
(function () {
    const api = <?php echo json_encode($apiUrl); ?>;

    /* ── Fortaleza de contraseña ── */
    document.getElementById('p-nueva').addEventListener('input', function () {
        const p = this.value;
        let s = 0;
        if (p.length >= 8)  s++;
        if (p.length >= 12) s++;
        if (/[A-Z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;
        const pct = Math.min(s / 5 * 100, 100);
        const colors = ['#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
        const labels = ['Muy débil','Débil','Aceptable','Fuerte','Muy fuerte'];
        const bar = document.getElementById('p-bar');
        bar.style.width      = pct + '%';
        bar.style.background = colors[Math.max(s - 1, 0)];
        document.getElementById('p-bar-lbl').textContent = p ? labels[Math.max(s - 1, 0)] : '';
    });

    function showMsg(id, ok, msg) {
        const el = document.getElementById(id);
        el.style.display    = 'block';
        el.style.background = ok ? '#f0fdf4' : '#fef2f2';
        el.style.border     = '1px solid ' + (ok ? '#bbf7d0' : '#fecaca');
        el.style.color      = ok ? '#166534' : '#b91c1c';
        el.innerHTML = (ok ? '<i class="fas fa-circle-check me-1"></i>' : '<i class="fas fa-circle-exclamation me-1"></i>') + msg;
    }

    /* ── Guardar info personal ── */
    document.getElementById('btn-guardar-info').addEventListener('click', function () {
        const btn    = this;
        const nombre = document.getElementById('p-nombre').value.trim();
        const email  = document.getElementById('p-email').value.trim();
        if (!nombre || !email) return showMsg('info-msg', false, 'Nombre y email son obligatorios.');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

        const fd = new FormData();
        fd.append('accion', 'info');
        fd.append('nombre', nombre);
        fd.append('email',  email);

        fetch(api, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function (res) {
                showMsg('info-msg', res.estado, res.mensaje);
                if (res.estado) {
                    document.getElementById('hero-nombre').textContent = nombre;
                    document.getElementById('hero-email').textContent  = email;
                    document.getElementById('info-email-summary').textContent = email;
                }
            })
            .catch(() => showMsg('info-msg', false, 'Error de conexión.'))
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Guardar cambios';
            });
    });

    /* ── Cambiar contraseña ── */
    document.getElementById('btn-guardar-pass').addEventListener('click', function () {
        const btn      = this;
        const actual   = document.getElementById('p-actual').value;
        const nueva    = document.getElementById('p-nueva').value;
        const confirmar = document.getElementById('p-confirmar').value;
        if (!actual || !nueva || !confirmar) return showMsg('pass-msg', false, 'Completa todos los campos.');
        if (nueva.length < 8) return showMsg('pass-msg', false, 'La nueva contraseña debe tener al menos 8 caracteres.');
        if (nueva !== confirmar) return showMsg('pass-msg', false, 'Las contraseñas nuevas no coinciden.');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Actualizando...';

        const fd = new FormData();
        fd.append('accion',    'password');
        fd.append('actual',    actual);
        fd.append('nueva',     nueva);
        fd.append('confirmar', confirmar);

        fetch(api, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function (res) {
                showMsg('pass-msg', res.estado, res.mensaje);
                if (res.estado) {
                    document.getElementById('p-actual').value    = '';
                    document.getElementById('p-nueva').value     = '';
                    document.getElementById('p-confirmar').value = '';
                    document.getElementById('p-bar').style.width = '0';
                    document.getElementById('p-bar-lbl').textContent = '';
                }
            })
            .catch(() => showMsg('pass-msg', false, 'Error de conexión.'))
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-key me-1"></i>Actualizar contraseña';
            });
    });
}());
</script>
</body>
</html>
