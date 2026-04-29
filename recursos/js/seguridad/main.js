// EGIDRA - Seguridad HSE (AJAX)

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll ────────────────────────────────────────────────
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.padding   = window.scrollY > 50 ? '0.5rem 0' : '1rem 0';
            navbar.style.boxShadow = window.scrollY > 50 ? '0 2px 20px rgba(0,0,0,0.3)' : 'none';
        }, { passive: true });
    }

    // ── Lanzar todas las peticiones en paralelo ───────────────────────
    Promise.all([
        fetch(BASE_URL + 'recursos/api/seguridad/estadisticas.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/seguridad/reglas.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/sobre-nosotros/certificaciones.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/inicio/stats.php').then(r => r.json()),
    ]).then(([estadisticas, reglas, certs, stats]) => {
        renderHseStats(estadisticas.success ? estadisticas.data : []);
        renderReglas(reglas.success ? reglas.data : []);
        renderCerts(certs.success ? certs.data : []);
        applyStats(stats.success ? stats.data : {}, certs.success ? certs.data.length : 0);
        initStatsObserver();
    }).catch(err => {
        console.error('Error cargando datos HSE:', err);
        renderHseStats([]);
        renderReglas([]);
        renderCerts([]);
        initStatsObserver();
    });

    // ────────────────────────────────────────────────────────────────
    // Render: Estadísticas HSE
    // ────────────────────────────────────────────────────────────────
    function renderHseStats(data) {
        const container = document.getElementById('hseStatsContainer');
        if (!container) return;

        if (!data.length) {
            // Fallback con datos representativos
            data = [
                { icono: 'fa-shield-halved', valor: '0',    etiqueta: 'Accidentes',       sub: 'últimos 5 años' },
                { icono: 'fa-clock',         valor: '+2M',  etiqueta: 'Horas-Hombre',     sub: 'sin incidentes' },
                { icono: 'fa-certificate',   valor: '—',    etiqueta: 'Certificaciones',  sub: 'internacionales' },
                { icono: 'fa-graduation-cap',valor: '100%', etiqueta: 'Personal Formado', sub: 'en HSE antes de operar' },
            ];
        }

        container.innerHTML = data.map(s => `
            <div class="col-6 col-md-3">
                <div class="hse-stat-item">
                    <div class="hse-stat-icon">
                        <i class="fas ${esc(s.icono || 'fa-shield-halved')} fa-2x text-warning"></i>
                    </div>
                    <h2 class="text-warning fw-bold mb-1" style="font-size:2.2rem;">${esc(s.valor)}</h2>
                    <p class="text-white fw-semibold mb-1">${esc(s.etiqueta)}</p>
                    ${s.sub ? `<p class="text-white-50 small mb-0">${esc(s.sub)}</p>` : ''}
                </div>
            </div>`).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Reglas de Oro
    // ────────────────────────────────────────────────────────────────
    function renderReglas(data) {
        const container = document.getElementById('reglasContainer');
        const titulo    = document.getElementById('reglasTitulo');
        if (!container) return;

        if (!data.length) {
            container.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-shield-halved fa-3x mb-3 opacity-50"></i>
                    <p>Las Reglas de Oro se publicarán desde el panel de administración.</p>
                </div>`;
            return;
        }

        if (titulo) titulo.textContent = `Las ${data.length} Reglas de Oro`;

        container.innerHTML = data.map(r => `
            <div class="col-md-6 col-lg-4">
                <div class="card rule-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rule-number">${esc(String(r.numero_orden))}</div>
                            <div class="rule-icon">
                                <i class="fas ${esc(r.icono || 'fa-shield-halved')} fa-lg text-warning"></i>
                            </div>
                            <h5 class="fw-bold mb-0">${esc(r.titulo)}</h5>
                        </div>
                        <p class="text-muted small mb-0">${esc(r.descripcion || '')}</p>
                    </div>
                </div>
            </div>`).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Certificaciones
    // ────────────────────────────────────────────────────────────────
    function renderCerts(data) {
        const container = document.getElementById('certsContainer');
        if (!container) return;

        if (!data.length) {
            container.innerHTML = `
                <div class="col-12 text-center py-4 text-muted">
                    <i class="fas fa-certificate fa-3x mb-3 opacity-50"></i>
                    <p>Las certificaciones se publicarán desde el panel de administración.</p>
                </div>`;
            return;
        }

        // Icono por organismo_emisor (fallback genérico)
        const orgIconos = {
            'Bureau Veritas': 'fa-award',
            'IMCA':           'fa-diving-mask',
            'IRATA':          'fa-rope',
            'DNV':            'fa-shield-halved',
            'DNV GL':         'fa-shield-halved',
        };

        container.innerHTML = data.map(c => {
            const partes = (c.nombre || '').split(' - ');
            const abrev  = esc(partes[0].trim());
            const desc   = esc(partes[1] ? partes[1].trim() : (c.organismo_emisor || ''));
            const icono  = orgIconos[c.organismo_emisor] || 'fa-certificate';
            const inner  = c.logo_url
                ? `<img src="${esc(c.logo_url)}" alt="${abrev}" style="max-height:44px;object-fit:contain;" class="mb-1">`
                : `<i class="fas ${icono} fa-2x text-warning"></i>`;
            const wrap = c.url_verificacion
                ? `<a href="${esc(c.url_verificacion)}" target="_blank" rel="noopener" class="text-decoration-none">`
                : '';
            const wrapClose = c.url_verificacion ? '</a>' : '';

            return `
            <div class="col-6 col-md-4 col-lg-2">
                ${wrap}
                <div class="card cert-card shadow-sm text-center p-4 h-100">
                    <div class="cert-icon">${inner}</div>
                    <h6 class="fw-bold mb-1">${abrev}</h6>
                    <p class="text-muted small mb-0">${desc}</p>
                </div>
                ${wrapClose}
            </div>`;
        }).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Stats: aplicar datos al DOM antes de animar
    // ────────────────────────────────────────────────────────────────
    function applyStats(data, certsCount) {
        const map = {
            proyectos:  data.proyectos  ?? 0,
            accidentes: 0,
            anios:      data.anios      ?? 0,
            certs:      certsCount      || 0,
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
            const duration = 2000;
            const delay    = Math.max(duration / target, 10);
            let current = 0;
            const suffix = counter.closest('.stat-item').querySelector('p').textContent.includes('%') ? '%' : '+';
            const iv = setInterval(() => {
                current++;
                if (current >= target) { counter.textContent = target + suffix; clearInterval(iv); }
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

    console.log('EGIDRA - Seguridad HSE cargado');
});
