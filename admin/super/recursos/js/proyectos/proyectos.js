/* EGIDRA — Proyectos dinámico */
(function () {
    'use strict';

    const tbody      = document.getElementById('tbody-proyectos');
    const searchEl   = document.getElementById('search-proy');
    const btnNuevo   = document.getElementById('btn-nuevo-proy');
    const btnGuardar = document.getElementById('btn-guardar-proy');
    const tituloMod  = document.getElementById('modal-proy-title');
    const filterCats = document.getElementById('filter-cats-proy');
    const btnTodos   = document.querySelector('[data-cat-filter=""]');

    const fId        = document.getElementById('proy-id');
    const fTitulo    = document.getElementById('proy-titulo');
    const fCat       = document.getElementById('proy-cat');
    const fCli       = document.getElementById('proy-cli');
    const fUbicacion = document.getElementById('proy-ubicacion');
    const fAno       = document.getElementById('proy-ano');
    const fDesc      = document.getElementById('proy-desc');
    const fImg       = document.getElementById('proy-img');
    const fDest      = document.getElementById('proy-dest');
    const fActivo    = document.getElementById('proy-activo');

    const CAT_COLORS = ['bp-blue','bp-purple','bp-green','bp-yellow','bp-red'];
    let todos = [];
    let catColores = {};
    let catActivo  = '';

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function poblarSelects() {
        fetch('../api/servicios/categorias/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) return;
                data.datos.forEach(function (c, i) {
                    catColores[c.id] = CAT_COLORS[i % CAT_COLORS.length];
                    const btn = document.createElement('button');
                    btn.className = 'btn-sec';
                    btn.dataset.catFilter = c.id;
                    btn.textContent = c.nombre;
                    filterCats.appendChild(btn);
                    fCat.insertAdjacentHTML('beforeend', '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');
                });
            });

        fetch('../api/clientes/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) return;
                data.datos.forEach(function (c) {
                    fCli.insertAdjacentHTML('beforeend', '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');
                });
            });
    }

    function applyFilter() {
        const q = searchEl.value.toLowerCase();
        let lista = todos;
        if (catActivo) lista = lista.filter(function (p) { return String(p.categoria_id) === String(catActivo); });
        if (q) lista = lista.filter(function (p) {
            return p.titulo.toLowerCase().includes(q) ||
                (p.cliente_nombre || '').toLowerCase().includes(q) ||
                (p.ubicacion || '').toLowerCase().includes(q);
        });
        renderTable(lista);
    }

    function renderTable(lista) {
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No hay proyectos.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map(function (p) {
            return '<tr>' +
                '<td style="font-weight:600;font-size:.82rem;">' + esc(p.titulo) + '</td>' +
                '<td><span class="badge-pill ' + (catColores[p.categoria_id] || 'bp-gray') + '">' + esc(p.categoria_nombre || '—') + '</span></td>' +
                '<td style="font-size:.8rem;">' + esc(p.cliente_nombre || '—') + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);"><i class="fas fa-map-marker-alt me-1"></i>' + esc(p.ubicacion || '—') + '</td>' +
                '<td style="font-size:.8rem;">' + esc(p.ano_finalizacion || '—') + '</td>' +
                '<td>' + (p.es_destacado ? '<i class="fas fa-star" style="color:var(--accent)"></i>' : '<i class="far fa-star" style="color:var(--border)"></i>') + '</td>' +
                '<td><div class="d-flex gap-1">' +
                    '<button class="btn-icon edit" data-id="' + p.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del" data-id="' + p.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div></td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        fetch('../api/proyectos/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { todos = data.estado ? data.datos : []; applyFilter(); })
            .catch(function () { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Error al cargar.</td></tr>'; });
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo proyecto';
        fId.value = ''; fTitulo.value = ''; fCat.value = ''; fCli.value = '';
        fUbicacion.value = ''; fAno.value = ''; fDesc.value = '';
        fImg.value = ''; fDest.checked = false; fActivo.checked = true;
        window.EgAdmin.openModal('modal-proy');
    }

    function abrirEditar(id) {
        const p = todos.find(function (x) { return x.id == id; });
        if (!p) return;
        tituloMod.textContent = 'Editar proyecto';
        fId.value        = p.id;
        fTitulo.value    = p.titulo              || '';
        fCat.value       = p.categoria_id        || '';
        fCli.value       = p.cliente_id          || '';
        fUbicacion.value = p.ubicacion           || '';
        fAno.value       = p.ano_finalizacion    || '';
        fDesc.value      = p.descripcion_tecnica || '';
        fImg.value       = p.imagen              || '';
        fDest.checked    = !!p.es_destacado;
        fActivo.checked  = !!p.activo;
        window.EgAdmin.openModal('modal-proy');
    }

    function guardar() {
        if (!fTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',               fId.value);
        fd.append('titulo',           fTitulo.value.trim());
        fd.append('categoria_id',     fCat.value);
        fd.append('cliente_id',       fCli.value);
        fd.append('ubicacion',        fUbicacion.value.trim());
        fd.append('ano_finalizacion', fAno.value || '');
        fd.append('descripcion',      fDesc.value.trim());
        fd.append('imagen',           fImg.value.trim());
        fd.append('es_destacado',     fDest.checked ? '1' : '0');
        fd.append('activo',           fActivo.checked ? '1' : '0');

        fetch('../api/proyectos/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-proy');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar proyecto?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/proyectos/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    function setActiveFilter(btn) {
        document.querySelectorAll('[data-cat-filter]').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
    }

    btnTodos?.addEventListener('click', function () {
        catActivo = '';
        setActiveFilter(this);
        applyFilter();
    });

    filterCats.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-cat-filter]');
        if (!btn) return;
        catActivo = btn.dataset.catFilter;
        setActiveFilter(btn);
        applyFilter();
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    tbody.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit');
        const del  = e.target.closest('.del');
        if (edit) abrirEditar(edit.dataset.id);
        if (del)  eliminar(del.dataset.id);
    });

    searchEl?.addEventListener('input', applyFilter);

    poblarSelects();
    cargar();
})();
