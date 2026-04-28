(function () {
    // Pre-rellenar email recordado
    const savedEmail = localStorage.getItem('eg_remember_email');
    if (savedEmail) {
        const emailInput = document.getElementById('email-input');
        if (emailInput) emailInput.value = savedEmail;
        const cb = document.getElementById('remember-me');
        if (cb) cb.checked = true;
    }

    // Toggle visibilidad de contraseña
    document.getElementById('toggle-pass').addEventListener('click', function () {
        const inp  = document.getElementById('pass-input');
        const icon = document.getElementById('toggle-icon');
        const show = inp.type === 'password';
        inp.type       = show ? 'text' : 'password';
        icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // Envío del formulario
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const email    = document.getElementById('email-input').value.trim();
        const password = document.getElementById('pass-input').value;
        const remember = document.getElementById('remember-me').checked;

        if (!email || !password) {
            showError('Por favor, complete todos los campos.');
            return;
        }

        if (remember) {
            localStorage.setItem('eg_remember_email', email);
        } else {
            localStorage.removeItem('eg_remember_email');
        }

        const btn = document.getElementById('btn-login');
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';

        const formData = new FormData();
        formData.append('email',      email);
        formData.append('contrasena', password);
        formData.append('recordarme', remember ? '1' : '0');

        fetch('./api/login.php', { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.estado && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.mensaje || 'Error al iniciar sesión.');
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fas fa-right-to-bracket me-2"></i>Iniciar Sesión';
                }
            })
            .catch(function () {
                showError('Error de conexión. Inténtelo de nuevo.');
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-right-to-bracket me-2"></i>Iniciar Sesión';
            });
    });

    function showError(msg) {
        let el = document.getElementById('login-error');
        if (!el) {
            el = document.createElement('div');
            el.id        = 'login-error';
            el.className = 'alert alert-danger mt-3 text-center py-2';
            document.getElementById('login-form').appendChild(el);
        }
        el.textContent = msg;
    }
})();
