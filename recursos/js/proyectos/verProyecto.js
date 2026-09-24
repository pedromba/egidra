// EGIDRA — Ver Proyecto

document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.style.padding   = window.scrollY > 50 ? '0.5rem 0' : '1rem 0';
            navbar.style.boxShadow = window.scrollY > 50 ? '0 2px 20px rgba(0,0,0,0.3)' : 'none';
        }, { passive: true });
    }

    cargarRelacionados();

    function cargarRelacionados() {
        const grid = document.getElementById('related-grid');
        if (!grid) return;

        fetch(BASE_URL + 'recursos/api/proyectos/listar.php')
            .then(r => r.json())
            .then(data => {
                const todos = (data.data || []).filter(p => p.id_proyecto !== CURRENT_ID);
                const muestra = todos.sort(() => Math.random() - 0.5).slice(0, 3);

                if (!muestra.length) {
                    grid.innerHTML = '<div class="col-12 text-center text-muted">No hay más proyectos disponibles.</div>';
                    return;
                }

                grid.innerHTML = muestra.map(p => {
                    const imgHtml = p.imagen_url
                        ? `<img src="${esc(p.imagen_url)}" alt="${esc(p.titulo)}" style="height:180px;object-fit:cover;width:100%;">`
                        : `<div class="bg-secondary d-flex align-items-center justify-content-center" style="height:180px;"><i class="fas ${esc(p.categoria_icono || 'fa-anchor')} fa-2x text-white-50"></i></div>`;

                    return `
                    <div class="col-md-6 col-lg-4">
                        <div class="card project-card shadow-sm h-100">
                            ${imgHtml}
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2">${esc(p.categoria || 'Proyecto')}</span>
                                <h6 class="fw-bold mb-1">${esc(p.titulo)}</h6>
                                <p class="text-muted small mb-0">${esc((p.descripcion_tecnica || '').substring(0, 90))}${(p.descripcion_tecnica || '').length > 90 ? '…' : ''}</p>
                            </div>
                            <div class="card-footer">
                                <a href="${BASE_URL}proyectos/verProyecto.php?id=${p.id_proyecto}" class="btn btn-sm btn-warning fw-semibold text-dark w-100">
                                    <i class="fas fa-eye me-1"></i>Ver proyecto
                                </a>
                            </div>
                        </div>
                    </div>`;
                }).join('');
            })
            .catch(() => {
                const grid = document.getElementById('related-grid');
                if (grid) grid.innerHTML = '';
            });
    }

    function esc(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
});
