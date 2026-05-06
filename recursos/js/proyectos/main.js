// EGIDRA - Proyectos (AJAX)

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
        fetch(BASE_URL + 'recursos/api/proyectos/listar.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/inicio/clientes.php').then(r => r.json()),
        fetch(BASE_URL + 'recursos/api/inicio/stats.php').then(r => r.json()),
    ]).then(([proyData, clientesData, statsData]) => {
        const categorias = proyData.categorias || [];
        const proyectos  = proyData.success ? proyData.data : [];
        const clientes   = clientesData.success ? clientesData.data : [];
        const stats      = statsData.success ? statsData.data : {};

        // Actualizar subtítulo del header
        if (proyectos.length) {
            const sub = document.getElementById('headerSubtitle');
            if (sub) sub.textContent = `Más de ${proyectos.length} proyectos ejecutados con éxito para las principales compañías del sector Oil & Gas.`;
        }

        // Actualizar CTA count
        if (proyectos.length) {
            const ctaCount = document.getElementById('ctaCount');
            if (ctaCount) ctaCount.textContent = '+' + proyectos.length + ' Proyectos';
        }

        // Stats: actualizar categorias y datos generales
        const statCat = document.querySelector('.stat-number[data-key="categorias"]');
        if (statCat) statCat.dataset.target = categorias.length;
        Object.entries(stats).forEach(([key, val]) => {
            const el = document.querySelector(`.stat-number[data-key="${key}"]`);
            if (el) el.dataset.target = val;
        });

        renderFiltros(categorias);
        renderProyectos(proyectos);
        renderClientes(clientes);
        initStatsObserver();
    }).catch(err => {
        console.error('Error cargando proyectos:', err);
        renderProyectos([]);
        initStatsObserver();
    });

    // ────────────────────────────────────────────────────────────────
    // Render: Botones de filtro
    // ────────────────────────────────────────────────────────────────
    function renderFiltros(categorias) {
        const container = document.getElementById('filterContainer');
        if (!container) return;

        const todos = `<button class="filter-btn active" data-filter="todos">
            <i class="fas fa-th-large me-1"></i>Todos
        </button>`;

        const catBtns = categorias.map(c => `
            <button class="filter-btn" data-filter="${esc(c.slug)}">
                <i class="fas ${esc(c.icono || 'fa-cog')} me-1"></i>${esc(c.nombre)}
            </button>`).join('');

        container.innerHTML = todos + catBtns;

        // Lógica de filtrado
        container.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                container.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filtrar(this.dataset.filter);
            });
        });
    }

    // ────────────────────────────────────────────────────────────────
    // Filtrar proyectos (show/hide)
    // ────────────────────────────────────────────────────────────────
    function filtrar(filtro) {
        const items = document.querySelectorAll('.project-item');
        const noRes = document.getElementById('no-results');
        let visible = 0;

        items.forEach(item => {
            const show = filtro === 'todos' || item.dataset.category === filtro;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noRes) noRes.classList.toggle('d-none', visible > 0);
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Grid de proyectos
    // ────────────────────────────────────────────────────────────────
    function renderProyectos(proyectos) {
        const grid = document.getElementById('projects-grid');
        const noRes = document.getElementById('no-results');
        if (!grid) return;

        if (!proyectos.length) {
            grid.innerHTML = '';
            if (noRes) {
                noRes.classList.remove('d-none');
                noRes.querySelector('h5').textContent = 'Aún no hay proyectos publicados';
                noRes.querySelector('p').textContent  = 'Los proyectos se publicarán desde el panel de administración.';
            }
            return;
        }

        grid.innerHTML = proyectos.map(p => {
            const slug    = p.categoria_slug || '';
            const icono   = esc(p.categoria_icono || 'fa-anchor');
            const imgHtml = p.imagen_url
                ? `<img src="${p.imagen_url}" alt="${esc(p.titulo)}" style="height:220px;object-fit:cover;width:100%;">`
                : `<div class="bg-secondary d-flex align-items-center justify-content-center" style="height:220px;"><i class="fas ${icono} fa-3x text-white-50"></i></div>`;
            const badge = esc(p.categoria || 'Proyecto');
            const year  = p.ano_finalizacion || '';
            const loc   = p.ubicacion ? `<span><i class="fas fa-map-marker-alt me-1 text-warning"></i>${esc(p.ubicacion)}</span>` : '';
            const cli   = p.cliente   ? `<span><i class="fas fa-building me-1 text-warning"></i>${esc(p.cliente)}</span>` : '';
            const yr    = year        ? `<span><i class="fas fa-calendar me-1 text-warning"></i>${esc(String(year))}</span>` : '';

            const detailUrl = BASE_URL + 'proyectos/verProyecto.php?id=' + p.id_proyecto;

            return `
            <div class="col-md-6 col-lg-4 project-item" data-category="${esc(slug)}">
                <div class="card project-card shadow-sm h-100">
                    ${imgHtml}
                    <div class="card-body">
                        <span class="badge bg-warning text-dark mb-2">${badge}</span>
                        <h5 class="fw-bold mb-0">${esc(p.titulo)}</h5>
                    </div>
                    <div class="card-footer d-flex align-items-center gap-2 flex-wrap">
                        ${loc}${cli}${yr}
                        <a href="${detailUrl}" class="ms-auto text-warning" title="Ver proyecto">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Clientes
    // ────────────────────────────────────────────────────────────────
    function renderClientes(clientes) {
        const grid = document.getElementById('clientesGrid');
        if (!grid) return;

        if (!clientes.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-3">Los clientes se publicarán desde el panel de administración.</div>';
            return;
        }

        grid.innerHTML = clientes.map(c => {
            if (c.logo_url) {
                return `<div class="col-4 col-md-2">
                    <div class="client-logo">
                        <img src="${esc(c.logo_url)}" alt="${esc(c.nombre)}"
                             style="max-height:50px;max-width:110px;object-fit:contain;filter:grayscale(1);opacity:.7;">
                    </div>
                </div>`;
            }
            return `<div class="col-4 col-md-2">
                <div class="client-logo"><span>${esc(c.iniciales || c.nombre)}</span></div>
            </div>`;
        }).join('');
    }

    // ── Stats: animación ──────────────────────────────────────────────
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

    console.log('EGIDRA - Proyectos cargado');
});
