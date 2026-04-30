// EGIDRA - Servicios (AJAX)

const CAT_IMAGES = {
    'buceo-subsea':     '../img/template/bg-offshore.jpg',
    'acceso-cuerda':    '../img/template/srv-cuerda.jpg',
    'logistica':        '../img/template/srv-logistica.jpg',
    'estudios-tecnicos':'../img/template/srv-tecnico.jpg',
};
const IMG_DEFAULT = '../img/template/bg-industrial.jpg';

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll ────────────────────────────────────────────────
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.padding   = '0.5rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.3)';
            } else {
                navbar.style.padding   = '1rem 0';
                navbar.style.boxShadow = 'none';
            }
        }, { passive: true });
    }

    // ── Cargar categorías + servicios ────────────────────────────────
    fetch(BASE_URL + 'recursos/api/servicios/categorias.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            if (!success || !data || !data.length) {
                renderSinCategorias();
                return;
            }

            // Actualizar scroll indicator con el primer slug
            const indicator = document.getElementById('scrollIndicatorLink');
            if (indicator) indicator.href = '#' + data[0].slug;

            // Actualizar stat de áreas de servicio
            const statCat = document.querySelector('.stat-number[data-key="categorias"]');
            if (statCat) statCat.dataset.target = data.length;

            renderNav(data);
            renderSecciones(data);
        })
        .catch(() => renderSinCategorias());

    // ── Cargar certificaciones ────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/sobre-nosotros/certificaciones.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            if (!success || !data.length) return;
            renderCerts(data);
        });

    // ── Cargar stats ──────────────────────────────────────────────────
    fetch(BASE_URL + 'recursos/api/inicio/stats.php')
        .then(r => r.json())
        .then(({ success, data }) => {
            if (!success) return;
            document.querySelectorAll('.stat-number[data-key]').forEach(el => {
                const v = data[el.dataset.key];
                if (v !== undefined) el.dataset.target = v;
            });
            initStatsObserver();
        })
        .catch(() => initStatsObserver());

    // ────────────────────────────────────────────────────────────────
    // Render: Nav de categorías
    // ────────────────────────────────────────────────────────────────
    function renderNav(cats) {
        const placeholder = document.getElementById('navPlaceholder');
        if (!placeholder) return;

        const nav = document.createElement('nav');
        nav.className = 'services-nav shadow-sm';
        nav.innerHTML = `
            <div class="container">
                <ul class="nav">
                    ${cats.map((c, i) => `
                    <li class="nav-item">
                        <a class="nav-link${i === 0 ? ' active' : ''}" href="#${esc(c.slug)}">
                            <i class="fas ${esc(c.icono || 'fa-cog')}"></i>${esc(c.nombre)}
                        </a>
                    </li>`).join('')}
                </ul>
            </div>`;

        placeholder.replaceWith(nav);

        // Scroll highlight y smooth scroll
        const navLinks = nav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', e => {
                const href = link.getAttribute('href');
                if (!href.startsWith('#')) return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offset = 140;
                    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
                }
            });
        });

        window.addEventListener('scroll', () => {
            let current = '';
            document.querySelectorAll('section[id]').forEach(s => {
                if (window.scrollY >= s.offsetTop - 180) current = s.id;
            });
            navLinks.forEach(a => {
                a.classList.toggle('active', a.getAttribute('href') === '#' + current);
            });
        }, { passive: true });
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Secciones de servicio
    // ────────────────────────────────────────────────────────────────
    function renderSecciones(cats) {
        const container = document.getElementById('serviciosContainer');
        if (!container) return;

        container.innerHTML = cats.map((cat, i) => {
            const imgIzq = i % 2 === 0;
            const bgAlt  = i % 2 !== 0;
            const img    = CAT_IMAGES[cat.slug] || IMG_DEFAULT;
            const subSrvs = (cat.servicios || []).map(s => `
                <div class="col-12">
                    <div class="card sub-service-card shadow-sm" style="position:relative;">
                        <div class="card-body d-flex align-items-start gap-3 p-3">
                            <div class="sub-service-icon">
                                <i class="fas ${esc(s.icono || 'fa-cog')} fa-lg text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">${esc(s.titulo)}</h6>
                                <p class="text-muted small mb-0">${esc(s.descripcion_breve || '')}</p>
                            </div>
                        </div>
                    </div>
                </div>`).join('');

            const imgCol = `
                <div class="col-lg-6 ${imgIzq ? 'mb-4 mb-lg-0' : 'order-lg-2 mb-4 mb-lg-0'}">
                    <img src="${img}" alt="${esc(cat.nombre)}" class="img-fluid rounded-3 shadow">
                </div>`;

            const contentCol = `
                <div class="col-lg-6 ${imgIzq ? '' : 'order-lg-1'}">
                    <div class="category-badge">
                        <i class="fas ${esc(cat.icono || 'fa-cog')}"></i> ${esc(cat.nombre)}
                    </div>
                    <h2 class="display-5 fw-bold mb-3">${esc(cat.nombre)}</h2>
                    ${cat.descripcion_breve ? `<p class="text-muted mb-4">${esc(cat.descripcion_breve)}</p>` : ''}
                    ${subSrvs ? `<div class="row g-3">${subSrvs}</div>` : ''}
                    <div class="mt-4">
                        <a href="../contacto/" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-2"></i>Solicitar este servicio
                        </a>
                    </div>
                </div>`;

            return `
            <section id="${esc(cat.slug)}" class="service-section${bgAlt ? ' bg-alt' : ''}">
                <div class="container">
                    <div class="row align-items-center">
                        ${imgCol}${contentCol}
                    </div>
                </div>
            </section>`;
        }).join('');
    }

    function renderSinCategorias() {
        const container = document.getElementById('serviciosContainer');
        if (container) {
            container.innerHTML = `
            <section class="py-5">
                <div class="container text-center py-5">
                    <i class="fas fa-hard-hat fa-4x text-warning mb-4 opacity-50"></i>
                    <h3 class="text-muted">Los servicios se publicarán desde el panel de administración.</h3>
                </div>
            </section>`;
        }
    }

    // ────────────────────────────────────────────────────────────────
    // Render: Tira de certificaciones
    // ────────────────────────────────────────────────────────────────
    function renderCerts(certs) {
        const container = document.getElementById('certsContainer');
        if (!container) return;

        const badges = certs.map(c => {
            const abrev = esc((c.nombre || '').split(' - ')[0].trim());
            const title = esc(c.nombre + (c.organismo_emisor ? ' — ' + c.organismo_emisor : ''));
            if (c.url_verificacion) {
                return `<a href="${esc(c.url_verificacion)}" target="_blank" rel="noopener"
                           class="badge bg-secondary fs-6 py-2 px-3 text-decoration-none" title="${title}">
                            <i class="fas fa-certificate me-1 text-warning"></i>${abrev}
                        </a>`;
            }
            return `<span class="badge bg-secondary fs-6 py-2 px-3" title="${title}">
                        <i class="fas fa-certificate me-1 text-warning"></i>${abrev}
                    </span>`;
        }).join('');

        container.innerHTML = `
        <section class="py-5 bg-dark">
            <div class="container">
                <div class="text-center mb-4">
                    <span class="text-warning fw-bold text-uppercase">Respaldo Internacional</span>
                    <h3 class="fw-bold text-white mt-2">Nuestras Certificaciones</h3>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-3">${badges}</div>
                <p class="text-center text-white-50 small mt-3 mb-0">
                    <a href="../seguridad/" class="text-warning text-decoration-none">
                        <i class="fas fa-shield-virus me-1"></i>Ver todas las certificaciones y Seguridad HSE
                    </a>
                </p>
            </div>
        </section>`;
    }

    // ────────────────────────────────────────────────────────────────
    // Stats: animación de contadores
    // ────────────────────────────────────────────────────────────────
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

    console.log('EGIDRA - Servicios cargado');
});
