// EGIDRA - Socios y Alianzas (AJAX)

const CERT_ICONOS = {
    'Bureau Veritas': 'fa-award',
    'IMCA':           'fa-water',
    'IRATA':          'fa-rope',
    'DNV':            'fa-ship',
    'DNV GL':         'fa-ship',
};

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
        fetch(BASE_URL + 'recursos/api/socios/listar.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/sobre-nosotros/certificaciones.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/inicio/stats.php').then(r => r.json()),
    ]).then(([sociosData, certsData, statsData]) => {
        const socios = sociosData.success ? sociosData.data : [];
        const certs  = certsData.success  ? certsData.data  : [];
        const stats  = statsData.success  ? statsData.data  : {};

        renderSocios(socios);
        renderCerts(certs);
        applyStats(stats, socios.length, certs.length);
        initStatsObserver();
    }).catch(err => {
        console.error('Error cargando socios:', err);
        renderSocios([]);
        renderCerts([]);
        initStatsObserver();
    });

    // ────────────────────────────────────────────────────────────────
    // Render: Socios
    // ────────────────────────────────────────────────────────────────
    function renderSocios(socios) {
        const grid = document.getElementById('sociosGrid');
        if (!grid) return;

        if (!socios.length) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-handshake fa-3x mb-3 opacity-50"></i>
                    <p>Los socios se publicarán desde el panel de administración.</p>
                </div>`;
            return;
        }

        grid.innerHTML = socios.map(s => {
            const badge = s.logo_url
                ? `<img src="${esc(s.logo_url)}" alt="${esc(s.nombre)}" style="max-height:44px;max-width:90px;object-fit:contain;">`
                : `<span style="font-size:.75rem;font-weight:800;color:#8a6000;letter-spacing:.5px;">${esc(s.iniciales)}</span>`;

            const link = s.url_web
                ? `<a href="${esc(s.url_web)}" target="_blank" rel="noopener noreferrer" class="socio-link">
                       <i class="fas fa-arrow-up-right-from-square me-1"></i>Visitar sitio oficial
                   </a>`
                : '';

            return `
            <div class="col-md-6">
                <div class="socio-card h-100">
                    <div class="socio-card-head">
                        <div class="socio-logo-badge">${badge}</div>
                        <div class="flex-grow-1">
                            <div class="socio-nombre">${esc(s.nombre)}</div>
                        </div>
                    </div>
                    ${s.descripcion ? `<p class="socio-desc">${esc(s.descripcion)}</p>` : ''}
                    ${link ? `<div class="socio-foot">${link}</div>` : ''}
                </div>
            </div>`;
        }).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Certificaciones (pills)
    // ────────────────────────────────────────────────────────────────
    function renderCerts(certs) {
        const container = document.getElementById('certsContainer');
        if (!container) return;

        if (!certs.length) {
            container.innerHTML = `
                <div class="col-12 text-muted" style="font-size:.85rem;">
                    Las certificaciones se publicarán desde el panel de administración.
                </div>`;
            return;
        }

        container.innerHTML = certs.map(c => {
            const partes = (c.nombre || '').split(' - ');
            const abrev  = esc(partes[0].trim());
            const sub    = esc(partes[1] ? partes[1].trim() : (c.organismo_emisor || ''));
            const icono  = CERT_ICONOS[c.organismo_emisor] || 'fa-certificate';
            const inner  = c.logo_url
                ? `<img src="${esc(c.logo_url)}" alt="${abrev}" style="max-height:24px;object-fit:contain;">`
                : `<i class="fas ${icono} fa-lg text-warning"></i>`;

            return `
            <div class="col-6">
                <div class="d-flex align-items-center p-3 bg-white rounded-3 shadow-sm cert-pill">
                    <span class="me-3 flex-shrink-0">${inner}</span>
                    <div>
                        <div class="fw-bold" style="font-size:.85rem;">${abrev}</div>
                        <div class="text-muted" style="font-size:.72rem;">${sub}</div>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Stats: aplicar datos al DOM antes de animar
    // ────────────────────────────────────────────────────────────────
    function applyStats(data, sociosCount, certsCount) {
        const map = {
            socios:    sociosCount || 0,
            certs:     certsCount  || 0,
            anios:     data.anios      ?? 0,
            proyectos: data.proyectos  ?? 0,
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

    // ── Helper escape HTML ────────────────────────────────────────────
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    console.log('EGIDRA - Socios cargado');
});
