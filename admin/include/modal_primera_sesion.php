<?php
/* Renderiza solo si el usuario aún no ha cambiado su contraseña inicial */
if (empty($_SESSION['primera_sesion'])) return;

if (!defined('RUTA_ADMIN')) {
    require_once __DIR__ . '/../../config/config.php';
}
$_api = RUTA_ADMIN . 'api/primera_contrasena.php';
?>

<!-- Modal: primera contraseña — no es descartable -->
<div class="modal-backdrop-custom show" id="modal-primera-sesion" style="z-index:2000;">
    <div class="modal-box" style="max-width:440px;">
        <div class="modal-head" style="background:var(--primary);border-radius:14px 14px 0 0;">
            <h6 style="color:#000;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-key"></i>Configura tu contraseña
            </h6>
        </div>
        <div class="modal-body">
            <p style="font-size:.83rem;color:var(--muted);margin-bottom:18px;">
                Tu cuenta fue creada con una contraseña temporal. Elige una contraseña personal antes de continuar.
            </p>

            <div class="mb-3">
                <label class="f-label">Nueva contraseña <span class="text-danger">*</span></label>
                <input class="f-input" id="ps1-nueva" type="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password">
            </div>

            <div class="mb-1">
                <label class="f-label">Confirmar contraseña <span class="text-danger">*</span></label>
                <input class="f-input" id="ps1-confirmar" type="password" placeholder="Repite la contraseña" autocomplete="new-password">
            </div>

            <!-- barra de fortaleza -->
            <div style="margin-top:10px;">
                <div style="height:4px;border-radius:4px;background:#e5e7eb;overflow:hidden;">
                    <div id="ps1-bar" style="height:100%;width:0;border-radius:4px;transition:width .3s,background .3s;"></div>
                </div>
                <div id="ps1-bar-label" style="font-size:.7rem;color:var(--muted);margin-top:4px;"></div>
            </div>

            <div id="ps1-error" style="display:none;margin-top:12px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:9px 12px;font-size:.78rem;color:#b91c1c;">
                <i class="fas fa-circle-exclamation me-1"></i><span id="ps1-error-txt"></span>
            </div>
        </div>
        <div class="modal-foot" style="justify-content:center;">
            <button class="btn-pri" id="ps1-btn" style="width:100%;justify-content:center;">
                <i class="fas fa-lock me-1"></i>Establecer contraseña
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const api    = <?php echo json_encode($_api); ?>;
    const inp1   = document.getElementById('ps1-nueva');
    const inp2   = document.getElementById('ps1-confirmar');
    const btn    = document.getElementById('ps1-btn');
    const bar    = document.getElementById('ps1-bar');
    const lbl    = document.getElementById('ps1-bar-label');
    const errBox = document.getElementById('ps1-error');
    const errTxt = document.getElementById('ps1-error-txt');

    function strength(p) {
        let s = 0;
        if (p.length >= 8)  s++;
        if (p.length >= 12) s++;
        if (/[A-Z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;
        return s;
    }

    inp1.addEventListener('input', function () {
        const s = strength(this.value);
        const pct = Math.min(s / 5 * 100, 100);
        const colors = ['#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
        const labels = ['Muy débil','Débil','Aceptable','Fuerte','Muy fuerte'];
        bar.style.width  = pct + '%';
        bar.style.background = colors[Math.max(s - 1, 0)];
        lbl.textContent  = this.value ? labels[Math.max(s - 1, 0)] : '';
    });

    function showError(msg) {
        errTxt.textContent = msg;
        errBox.style.display = 'block';
    }

    btn.addEventListener('click', function () {
        errBox.style.display = 'none';
        const p1 = inp1.value;
        const p2 = inp2.value;

        if (p1.length < 8)      return showError('La contraseña debe tener al menos 8 caracteres.');
        if (p1 !== p2)           return showError('Las contraseñas no coinciden.');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';

        const fd = new FormData();
        fd.append('nueva', p1);
        fd.append('confirmar', p2);

        fetch(api, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(function (res) {
                if (res.estado) {
                    document.getElementById('modal-primera-sesion').style.opacity = '0';
                    document.getElementById('modal-primera-sesion').style.transition = 'opacity .3s';
                    setTimeout(function () {
                        document.getElementById('modal-primera-sesion').remove();
                    }, 300);
                } else {
                    showError(res.mensaje || 'Error al guardar.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-lock me-1"></i>Establecer contraseña';
                }
            })
            .catch(function () {
                showError('Error de conexión. Inténtalo de nuevo.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lock me-1"></i>Establecer contraseña';
            });
    });
}());
</script>
