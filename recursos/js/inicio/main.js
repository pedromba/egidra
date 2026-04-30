// EGIDRA - JavaScript Página de Inicio

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll ────────────────────────────────────────────────
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.style.padding   = '0.5rem 0';
            navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.3)';
        } else {
            navbar.style.padding   = '1rem 0';
            navbar.style.boxShadow = 'none';
        }
    });

    // ── Smooth scroll ────────────────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // ── Empresa: datos dinámicos desde API ──────────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/empresa.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            if (!success || !data) return;
            document.title = data.nombre + ' - Servicios Industriales Especializados';
            const metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc) metaDesc.setAttribute('content', data.nombre + ' - ' + (data.slogan || 'Expertos en Soluciones Industriales') + '.');
            const heroSlogan = document.getElementById('heroSlogan');
            if (heroSlogan && data.slogan) heroSlogan.textContent = data.slogan;
            const heroDesc = document.getElementById('heroDesc');
            if (heroDesc && data.descripcion) heroDesc.textContent = data.descripcion;
            const aboutDesc = document.getElementById('aboutDesc');
            if (aboutDesc && data.descripcion) aboutDesc.textContent = data.descripcion;
            if (data.img_nosotros_url) {
                const col = document.getElementById('imgNosotrosCol');
                if (col) col.innerHTML = `<img src="${esc(data.img_nosotros_url)}" alt="Equipo ${esc(data.nombre)}" class="img-fluid rounded-3 shadow" style="width:100%;height:400px;object-fit:cover;">`;
            }
            const partnersTrust = document.getElementById('partnersTrust');
            if (partnersTrust && data.nombre) partnersTrust.innerHTML = `Todas nuestras operaciones están respaldadas por certificaciones internacionales vigentes. ${esc(data.nombre)} es miembro activo de los principales organismos de la industria subsea y rope access.`;
            if (data.telefono) {
                const telDiv = document.getElementById('contactTelDiv');
                const telSpan = document.getElementById('contactTel');
                if (telSpan) telSpan.textContent = data.telefono;
                if (telDiv) telDiv.style.display = '';
            }
            const emailSpan = document.getElementById('contactEmail');
            if (emailSpan) emailSpan.textContent = data.email || 'info@egidra.com';
            const dirSpan = document.getElementById('contactDir');
            if (dirSpan) dirSpan.textContent = (data.ciudad || 'Malabo') + ', ' + (data.pais || 'Guinea Ecuatorial');
        });

    // ── Stats: carga real desde API ──────────────────────────────────
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
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statsSection);
    }

    function animateCounters() {
        document.querySelectorAll('.stat-number').forEach(counter => {
            const target   = parseInt(counter.dataset.target) || 0;
            const duration = 2000;
            const steps    = Math.max(target, 1);
            const delay    = duration / steps;
            let current = 0;
            const suffix = counter.closest('.stat-item').querySelector('p')
                .textContent.includes('%') ? '%' : '+';
            const interval = setInterval(() => {
                current++;
                if (current >= target) {
                    counter.textContent = target + suffix;
                    clearInterval(interval);
                } else {
                    counter.textContent = current;
                }
            }, Math.max(delay, 10));
        });
    }

    // ── Proyectos destacados ─────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/proyectos.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            const container = document.getElementById('proyectosContainer');
            if (!container) return;
            if (!success || !data.length) {
                container.innerHTML = renderProyectosFallback();
                return;
            }
            const firstWithImg = data.find(p => p.imagen_url);
            if (firstWithImg) {
                const hseCol = document.getElementById('imgHSECol');
                if (hseCol) hseCol.innerHTML = `<img src="${esc(firstWithImg.imagen_url)}" alt="Seguridad HSE" class="img-fluid rounded-3 shadow" style="width:100%;height:400px;object-fit:cover;">`;
            }
            container.innerHTML = data.map(p => {
                const img = p.imagen_url
                    ? `<img src="${p.imagen_url}" class="card-img-top" alt="${esc(p.titulo)}" style="height:200px;object-fit:cover;">`
                    : `<div class="bg-secondary d-flex align-items-center justify-content-center" style="height:200px;"><i class="fas fa-${p.icono || 'anchor'} fa-3x text-white-50"></i></div>`;
                return `
                <div class="col-md-4">
                    <div class="card project-card border-0 shadow-sm h-100">
                        ${img}
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">${esc(p.categoria || 'Proyecto')}</span>
                            <h5 class="card-title fw-bold">${esc(p.titulo)}</h5>
                            <p class="card-text text-muted small">${esc(p.descripcion_tecnica || '')}</p>
                            ${p.ubicacion ? `<span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>${esc(p.ubicacion)}</span>` : ''}
                        </div>
                    </div>
                </div>`;
            }).join('');
        })
        .catch(() => {
            const c = document.getElementById('proyectosContainer');
            if (c) c.innerHTML = renderProyectosFallback();
        });

    function renderProyectosFallback() {
        return `
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
            <p>Los proyectos se mostrarán aquí una vez publicados desde el panel de administración.</p>
        </div>`;
    }

    // ── Clientes logo strip ──────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/clientes.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            const row = document.getElementById('clientesRow');
            if (!row) return;
            if (!success || !data.length) {
                row.innerHTML = '<div class="col-12 text-center text-muted opacity-50 py-2">Clientes se añadirán desde el panel de administración.</div>';
                return;
            }
            row.innerHTML = data.map(c => {
                if (c.logo_url) {
                    return `<div class="col-4 col-md-2 mb-3 mb-md-0">
                        <img src="${esc(c.logo_url)}" alt="${esc(c.nombre)}" style="max-height:50px;max-width:120px;object-fit:contain;filter:grayscale(1) brightness(1.5);opacity:.6;">
                    </div>`;
                }
                return `<div class="col-4 col-md-2 mb-3 mb-md-0">
                    <h4 class="text-muted opacity-50">${esc(c.iniciales || c.nombre)}</h4>
                </div>`;
            }).join('');
        });

    // ── Socios ───────────────────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/socios.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            const container = document.getElementById('sociosContainer');
            if (!container) return;

            // Quitar loader
            const loader = document.getElementById('sociosLoader');
            if (loader) loader.remove();

            if (!success || !data.length) {
                container.innerHTML = '<div class="col-12 text-center text-muted py-4">Los socios se publicarán desde el panel de administración.</div>';
                return;
            }

            container.innerHTML = data.map(s => {
                const logo = s.logo_url
                    ? `<img src="${esc(s.logo_url)}" alt="${esc(s.nombre)}" style="max-height:60px;max-width:140px;object-fit:contain;" class="mb-2">`
                    : `<div class="partner-logo">${esc(s.iniciales)}</div>`;
                const link = s.url_web
                    ? `<a href="${esc(s.url_web)}" target="_blank" rel="noopener noreferrer" class="partner-link"><i class="fas fa-arrow-up-right-from-square me-1"></i>Ver sitio</a>`
                    : '';
                return `
                <div class="col-6 col-md-3">
                    <div class="partner-card">
                        ${logo}
                        <div class="partner-name">${esc(s.nombre)}</div>
                        <p class="partner-desc">${esc(s.descripcion || '')}</p>
                        ${link}
                    </div>
                </div>`;
            }).join('');
        });

    // ── Hover en service cards ────────────────────────────────────────
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('mouseenter', function () { this.style.zIndex = '10'; });
        card.addEventListener('mouseleave', function () { this.style.zIndex = '1'; });
    });

    // ── Tooltips Bootstrap ────────────────────────────────────────────
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        .map(el => new bootstrap.Tooltip(el));

    // ── Lazy loading imágenes ─────────────────────────────────────────
    if ('IntersectionObserver' in window) {
        const imgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) { img.src = img.dataset.src; observer.unobserve(img); }
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(img => imgObserver.observe(img));
    }

    // ── Helper escape HTML ────────────────────────────────────────────
    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    console.log('EGIDRA - Inicio cargado correctamente');
});
