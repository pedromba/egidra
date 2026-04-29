// EGIDRA - Contacto (AJAX)

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll ────────────────────────────────────────────────
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.padding   = window.scrollY > 50 ? '0.5rem 0' : '1rem 0';
            navbar.style.boxShadow = window.scrollY > 50 ? '0 2px 20px rgba(0,0,0,0.3)' : 'none';
        }, { passive: true });
    }

    // ── Lanzar peticiones en paralelo ────────────────────────────────
    Promise.all([
        fetch(BASE_URL + 'recursos/api/inicio/empresa.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/inicio/stats.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/servicios/categorias.php').then(r => r.json()),
    ]).then(([empData, statsData, catsData]) => {
        const empresa = empData.success   ? empData.data   : {};
        const stats   = statsData.success ? statsData.data : {};
        const cats    = catsData.success  ? catsData.data  : [];

        renderContactInfo(empresa);
        renderAsuntoOptions(cats);
        applyStats(stats);
        initStatsObserver();
    }).catch(err => {
        console.error('Error cargando datos de contacto:', err);
        initStatsObserver();
    });

    // ────────────────────────────────────────────────────────────────
    // Render: tarjetas de información de contacto
    // ────────────────────────────────────────────────────────────────
    function renderContactInfo(emp) {
        const container = document.getElementById('contactInfoCards');
        if (!container) return;

        const cards = [];

        if (emp.telefono) {
            cards.push(`
            <div class="card info-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="info-icon"><i class="fas fa-phone fa-lg text-warning"></i></div>
                    <div>
                        <p class="fw-bold mb-0">Teléfono</p>
                        <p class="text-muted small mb-0">
                            <a href="tel:${esc(emp.telefono)}" class="text-muted text-decoration-none">${esc(emp.telefono)}</a>
                        </p>
                        <p class="text-muted small mb-0">Disponible 24/7 emergencias</p>
                    </div>
                </div>
            </div>`);
        }

        if (emp.email) {
            cards.push(`
            <div class="card info-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="info-icon"><i class="fas fa-envelope fa-lg text-warning"></i></div>
                    <div>
                        <p class="fw-bold mb-0">Correo Electrónico</p>
                        <p class="text-muted small mb-0">
                            <a href="mailto:${esc(emp.email)}" class="text-muted text-decoration-none">${esc(emp.email)}</a>
                        </p>
                        <p class="text-muted small mb-0">Respuesta en &lt; 24 horas</p>
                    </div>
                </div>
            </div>`);
        }

        const loc = [emp.ciudad, emp.pais].filter(Boolean).map(esc).join(', ');
        if (loc || emp.direccion) {
            cards.push(`
            <div class="card info-card shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="info-icon"><i class="fas fa-map-marker-alt fa-lg text-warning"></i></div>
                    <div>
                        <p class="fw-bold mb-0">Sede Principal</p>
                        ${emp.direccion ? `<p class="text-muted small mb-0">${esc(emp.direccion)}</p>` : ''}
                        ${loc           ? `<p class="text-muted small mb-0">${loc}</p>` : ''}
                    </div>
                </div>
            </div>`);
        }

        cards.push(`
        <div class="card info-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="info-icon"><i class="fas fa-clock fa-lg text-warning"></i></div>
                <div>
                    <p class="fw-bold mb-0">Horario Oficina</p>
                    <p class="text-muted small mb-0">Lun–Vie: 08:00 – 18:00</p>
                    <p class="text-muted small mb-0">Guardia operativa 24/7</p>
                </div>
            </div>
        </div>`);

        // Redes sociales
        const redes = [];
        if (emp.linkedin)  redes.push(`<a href="${esc(emp.linkedin)}"  target="_blank" rel="noopener" class="btn btn-dark btn-sm px-3 py-2" aria-label="LinkedIn"><i class="fab fa-linkedin fa-lg"></i></a>`);
        if (emp.facebook)  redes.push(`<a href="${esc(emp.facebook)}"  target="_blank" rel="noopener" class="btn btn-dark btn-sm px-3 py-2" aria-label="Facebook"><i class="fab fa-facebook fa-lg"></i></a>`);
        if (emp.instagram) redes.push(`<a href="${esc(emp.instagram)}" target="_blank" rel="noopener" class="btn btn-dark btn-sm px-3 py-2" aria-label="Instagram"><i class="fab fa-instagram fa-lg"></i></a>`);

        if (redes.length) {
            cards.push(`
            <div class="card info-card shadow-sm">
                <div class="card-body p-4">
                    <p class="fw-bold mb-3">Síguenos</p>
                    <div class="d-flex gap-3">${redes.join('')}</div>
                </div>
            </div>`);
        }

        container.innerHTML = cards.join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Render: opciones del select "Asunto" desde categorías de servicios
    // ────────────────────────────────────────────────────────────────
    function renderAsuntoOptions(cats) {
        const select = document.getElementById('asunto');
        if (!select || !cats.length) return;

        const extras = ['Seguridad HSE', 'Información general', 'Otro'];
        const opts = cats.map(c => `<option value="${esc(c.nombre)}">${esc(c.nombre)}</option>`).join('');
        const extOpts = extras.map(e => `<option value="${esc(e)}">${esc(e)}</option>`).join('');

        select.innerHTML = `<option value="">Seleccione un asunto...</option>${opts}${extOpts}`;
    }

    // ────────────────────────────────────────────────────────────────
    // Stats: aplicar datos al DOM antes de animar
    // ────────────────────────────────────────────────────────────────
    function applyStats(data) {
        const map = {
            proyectos: data.proyectos ?? 0,
            clientes:  data.clientes  ?? 0,
            anios:     data.anios     ?? 0,
        };
        document.querySelectorAll('.stat-number[data-key]').forEach(el => {
            const v = map[el.dataset.key];
            if (v !== undefined) el.dataset.target = v;
        });
    }

    // ── Stats: animación con IntersectionObserver ─────────────────────
    function initStatsObserver() {
        const section = document.querySelector('.stats-section');
        if (!section) return;
        let animated = false;
        new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.3 }).observe(section);
    }

    function animateCounters() {
        document.querySelectorAll('.stat-number').forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            if (!target) { counter.textContent = '0'; return; }
            const delay  = Math.max(2000 / target, 10);
            let current  = 0;
            const suffix = counter.closest('.stat-item').querySelector('p').textContent.includes('%') ? '%' : '+';
            const iv = setInterval(() => {
                current++;
                if (current >= target) { counter.textContent = target + suffix; clearInterval(iv); }
                else counter.textContent = current;
            }, delay);
        });
    }

    // ────────────────────────────────────────────────────────────────
    // Formulario: envío AJAX
    // ────────────────────────────────────────────────────────────────
    const form    = document.getElementById('contact-form');
    const btn     = document.getElementById('submit-btn');
    const alert   = document.getElementById('form-alert');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validación cliente
            let valid = true;
            form.querySelectorAll('[required]').forEach(field => {
                field.classList.remove('is-invalid');
                if (!field.value.trim()) { field.classList.add('is-invalid'); valid = false; }
            });
            const emailEl = document.getElementById('email');
            if (emailEl && emailEl.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value)) {
                emailEl.classList.add('is-invalid');
                valid = false;
            }
            if (!valid) return;

            // Estado de carga
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Enviando...';
            if (alert) alert.classList.add('d-none');

            fetch(BASE_URL + 'recursos/api/contacto/enviar.php', {
                method: 'POST',
                body: new FormData(form),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    showAlert('success', '<i class="fas fa-check-circle me-2"></i>' + data.mensaje);
                } else {
                    showAlert('danger', '<i class="fas fa-exclamation-circle me-2"></i>' + data.mensaje);
                }
            })
            .catch(() => {
                showAlert('danger', '<i class="fas fa-exclamation-circle me-2"></i>Error de conexión. Por favor inténtelo de nuevo.');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Mensaje';
            });
        });

        // Limpiar is-invalid al escribir
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', () => field.classList.remove('is-invalid'));
        });
    }

    function showAlert(type, html) {
        if (!alert) return;
        alert.className = `alert alert-${type} mt-3`;
        alert.innerHTML = html;
        alert.classList.remove('d-none');
        alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // ── Helper escape HTML ────────────────────────────────────────────
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    console.log('EGIDRA - Contacto cargado');
});
