// EGIDRA - JavaScript Sobre Nosotros

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll ────────────────────────────────────────────────
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.style.padding   = '0.5rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.3)';
            } else {
                navbar.style.padding   = '1rem 0';
                navbar.style.boxShadow = 'none';
            }
        });
    }

    // ── Valores corporativos ─────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/sobre-nosotros/valores.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            // Resumen en tarjeta MVV
            const resumen = document.getElementById('valoresMVVResumen');
            if (resumen && success && data.length) {
                resumen.textContent = data.map(v => v.titulo).join(', ') + ' son los pilares que guían cada una de nuestras operaciones.';
            }

            // Sección completa de valores
            const container = document.getElementById('valoresContainer');
            if (!container) return;
            if (!success || !data.length) {
                container.innerHTML = '<div class="col-12 text-center text-muted py-4">Los valores se publicarán desde el panel de administración.</div>';
                return;
            }
            container.innerHTML = data.map(v => `
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3"><i class="fas ${esc(v.icono || 'fa-star')} fa-3x text-warning"></i></div>
                            <h5 class="card-title fw-bold">${esc(v.titulo)}</h5>
                            <p class="card-text text-muted">${esc(v.descripcion || '')}</p>
                        </div>
                    </div>
                </div>`).join('');
        });

    // ── Equipo ───────────────────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/sobre-nosotros/equipo.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            const container = document.getElementById('equipoContainer');
            if (!container) return;
            if (!success || !data.length) {
                container.innerHTML = renderEquipoFallback();
                return;
            }
            // Centrar si hay pocos miembros
            const n = data.length;
            container.classList.add('justify-content-center');
            const col = n === 1 ? 'col-sm-10 col-md-6 col-lg-4'
                      : n === 2 ? 'col-sm-6 col-md-5 col-lg-4'
                      :           'col-md-6 col-lg-3';

            container.innerHTML = data.map(m => {
                const foto = m.foto_url
                    ? `<img src="${esc(m.foto_url)}" alt="${esc(m.nombre)}" class="rounded-circle mb-3 mx-auto d-block" style="width:80px;height:80px;object-fit:cover;">`
                    : `<div class="team-icon mb-3 mx-auto"><i class="fas fa-user-tie fa-3x text-warning"></i></div>`;
                const linkedin = m.linkedin
                    ? `<a href="${esc(m.linkedin)}" target="_blank" rel="noopener" class="text-warning mt-2 d-block small"><i class="fab fa-linkedin me-1"></i>LinkedIn</a>`
                    : '';
                return `
                <div class="${col}">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            ${foto}
                            <h5 class="card-title fw-bold">${esc(m.nombre)}</h5>
                            <p class="card-text text-warning small fw-bold">${esc(m.cargo || '')}</p>
                            <p class="card-text text-muted small">${esc(m.bio || '')}</p>
                            ${linkedin}
                        </div>
                    </div>
                </div>`;
            }).join('');
        })
        .catch(() => {
            const c = document.getElementById('equipoContainer');
            if (c) c.innerHTML = renderEquipoFallback();
        });

    function renderEquipoFallback() {
        return `
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
            <p>El equipo se publicará desde el panel de administración.</p>
        </div>`;
    }

    // ── Certificaciones ──────────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/sobre-nosotros/certificaciones.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            const container = document.getElementById('certificacionesContainer');
            if (!container) return;
            if (!success || !data.length) {
                container.innerHTML = '<div class="col-12 text-muted small">Las certificaciones se publicarán desde el panel de administración.</div>';
                return;
            }
            container.innerHTML = data.map(c => {
                const inner = c.logo_url
                    ? `<img src="${esc(c.logo_url)}" alt="${esc(c.nombre)}" style="max-height:32px;max-width:90px;object-fit:contain;" class="me-2">`
                    : `<i class="fas fa-certificate fa-2x text-warning me-3"></i>`;
                const nombre = c.url_verificacion
                    ? `<a href="${esc(c.url_verificacion)}" target="_blank" rel="noopener" class="fw-bold text-decoration-none text-dark">${esc(c.nombre)}</a>`
                    : `<span class="fw-bold">${esc(c.nombre)}</span>`;
                return `
                <div class="col-6">
                    <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm">
                        ${inner}${nombre}
                    </div>
                </div>`;
            }).join('');
        });

    // ── Stats (proyectos y clientes desde API) ───────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/stats.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            if (!success) return;
            document.querySelectorAll('.stat-number[data-key]').forEach(el => {
                const key = el.dataset.key;
                if (data[key] !== undefined) el.dataset.target = data[key];
            });
            initStatsObserver();
        })
        .catch(() => initStatsObserver());

    function initStatsObserver() {
        const statsSection = document.querySelector('.stats-section');
        if (!statsSection) return;
        let animated = false;
        new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.3 }).observe(statsSection);
    }

    function animateCounters() {
        document.querySelectorAll('.stat-number').forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            if (!target) { counter.textContent = '0'; return; }
            const duration = 2000;
            const delay    = Math.max(duration / target, 10);
            let current = 0;
            const suffix = counter.closest('.stat-item').querySelector('p')
                .textContent.includes('%') ? '%' : '+';
            const interval = setInterval(() => {
                current++;
                if (current >= target) { counter.textContent = target + suffix; clearInterval(interval); }
                else counter.textContent = current;
            }, delay);
        });
    }

    // ── Helper escape HTML ────────────────────────────────────────────
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    console.log('EGIDRA - Sobre Nosotros cargado correctamente');
});
