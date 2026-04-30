/* EGIDRA — Servicios Super */
(function () {
    'use strict';

    const grid        = document.getElementById('grid-servicios');
    const statsEl     = document.getElementById('stats-servicios');
    const btnNuevoSvc = document.getElementById('btn-nuevo-svc');
    const btnNuevaCat = document.getElementById('btn-nueva-cat');
    const btnTabSvc   = document.getElementById('btn-tab-svc');
    const btnTabCat   = document.getElementById('btn-tab-cat');
    const btnGuardSvc = document.getElementById('btn-guardar-svc');
    const btnGuardCat = document.getElementById('btn-guardar-cat');
    const tituloSvc   = document.getElementById('modal-svc-title');
    const tituloCat   = document.getElementById('modal-cat-title');

    const fSvcId    = document.getElementById('svc-id');
    const fSvcCat   = document.getElementById('svc-cat');
    const fSvcTitulo= document.getElementById('svc-titulo');
    const fSvcDesc  = document.getElementById('svc-desc');
    const fSvcIcono = document.getElementById('svc-icono');
    const fSvcOrden = document.getElementById('svc-orden');
    const fSvcDest  = document.getElementById('svc-dest');
    const fSvcActivo= document.getElementById('svc-activo');

    const fCatId    = document.getElementById('cat-id');
    const fCatNombre= document.getElementById('cat-nombre');
    const fCatIcono = document.getElementById('cat-icono');
    const fCatDesc  = document.getElementById('cat-desc');
    const fCatOrden = document.getElementById('cat-orden');
    const fCatActivo= document.getElementById('cat-activo');

    let categorias      = [];
    let vistaActual     = 'servicios';
    let currentVerSvcId = null;

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function poblarSelectCat() {
        fSvcCat.innerHTML = '<option value="">— Seleccionar —</option>';
        categorias.forEach(function (c) {
            fSvcCat.insertAdjacentHTML('beforeend', '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');
        });
    }

    function actualizarStats() {
        let total = 0;
        categorias.forEach(function (c) { total += (c.servicios || []).length; });
        if (statsEl) statsEl.textContent =
            total + ' servicio' + (total !== 1 ? 's' : '') +
            ' · ' + categorias.length + ' categorí' + (categorias.length !== 1 ? 'as' : 'a');
    }

    /* ══════════════ TABS ══════════════ */

    function cambiarVista(v) {
        vistaActual = v;
        btnTabSvc.classList.toggle('activo', v === 'servicios');
        btnTabCat.classList.toggle('activo', v === 'categorias');
        btnNuevoSvc.style.display = v === 'servicios'  ? '' : 'none';
        btnNuevaCat.style.display = v === 'categorias' ? '' : 'none';
        if (v === 'servicios') renderTabla(categorias);
        else                   renderCategorias(categorias);
    }

    /* ══════════════ VISTA SERVICIOS (TABLA) ══════════════ */

    function renderTabla(cats) {
        const rows = [];
        cats.forEach(function (c) {
            (c.servicios || []).forEach(function (s) {
                rows.push({
                    id: s.id, titulo: s.titulo, descripcion_breve: s.descripcion_breve,
                    icono: s.icono, orden: s.orden, es_destacado: s.es_destacado,
                    activo: s.activo, categoria_id: c.id, categoria_nombre: c.nombre
                });
            });
        });

        if (!rows.length) {
            grid.innerHTML = '<div class="text-center text-muted py-5">No hay servicios registrados.</div>';
            return;
        }

        grid.innerHTML =
            '<div class="table-wrap"><table class="svc-table">' +
            '<thead><tr>' +
            '<th>Servicio</th><th>Categoría</th>' +
            '<th class="text-center">Orden</th><th class="text-center">Destacado</th>' +
            '<th class="text-center">Estado</th><th class="text-center">Acciones</th>' +
            '</tr></thead><tbody>' +
            rows.map(function (s) {
                return '<tr>' +
                    '<td><div class="d-flex align-items-center gap-2">' +
                        '<span class="svc-row-icon"><i class="fas ' + esc(s.icono || 'fa-cog') + '"></i></span>' +
                        '<span class="svc-row-title">' + esc(s.titulo) + '</span>' +
                    '</div></td>' +
                    '<td><span class="svc-cat-badge">' + esc(s.categoria_nombre) + '</span></td>' +
                    '<td class="text-center"><span class="svc-orden-num">' + (s.orden || 0) + '</span></td>' +
                    '<td class="text-center">' + (s.es_destacado
                        ? '<i class="fas fa-star" style="color:#f59e0b;font-size:.85rem;"></i>'
                        : '<span style="color:var(--muted);">—</span>') + '</td>' +
                    '<td class="text-center">' + (s.activo
                        ? '<span class="badge-estado activo">Activo</span>'
                        : '<span class="badge-estado inactivo">Inactivo</span>') + '</td>' +
                    '<td><div class="d-flex gap-1 justify-content-center">' +
                        '<button class="btn-icon ver-svc" data-id="' + s.id + '" title="Ver detalles"><i class="fas fa-eye"></i></button>' +
                        '<button class="btn-icon edit-svc" data-id="' + s.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del-svc" data-id="' + s.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div></td>' +
                '</tr>';
            }).join('') +
            '</tbody></table></div>';
    }

    /* ══════════════ VISTA CATEGORÍAS ══════════════ */

    function renderCategorias(cats) {
        if (!cats.length) {
            grid.innerHTML = '<div class="text-center text-muted py-5">No hay categorías registradas.</div>';
            return;
        }
        grid.innerHTML = cats.map(function (c) {
            const n = (c.servicios || []).length;
            return '<div class="cat-list-row">' +
                '<div class="cat-list-icon"><i class="fas ' + esc(c.icono || 'fa-folder') + '"></i></div>' +
                '<div class="cat-list-info">' +
                    '<h6>' + esc(c.nombre) + '</h6>' +
                    '<p>' + esc(c.descripcion_breve || '—') + '</p>' +
                '</div>' +
                '<span class="cat-list-count">' + n + ' servicio' + (n !== 1 ? 's' : '') + '</span>' +
                '<button class="btn-icon edit-cat" data-id="' + c.id + '" title="Editar categoría"><i class="fas fa-pen"></i></button>' +
            '</div>';
        }).join('');
    }

    /* ══════════════ CARGA ══════════════ */

    function cargar() {
        grid.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/servicios/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                categorias = data.estado ? data.datos : [];
                poblarSelectCat();
                actualizarStats();
                if (vistaActual === 'servicios') renderTabla(categorias);
                else                             renderCategorias(categorias);
            })
            .catch(function () {
                grid.innerHTML = '<div class="text-center text-muted py-4">Error al cargar.</div>';
            });
    }

    /* ══════════════ CRUD SERVICIOS ══════════════ */

    function findSvc(id) {
        for (var i = 0; i < categorias.length; i++) {
            var found = (categorias[i].servicios || []).find(function (s) { return s.id == id; });
            if (found) {
                found.categoria_id     = categorias[i].id;
                found.categoria_nombre = categorias[i].nombre;
                return found;
            }
        }
        return null;
    }

    function abrirNuevoSvc() {
        tituloSvc.textContent = 'Nuevo servicio';
        fSvcId.value = ''; fSvcTitulo.value = ''; fSvcDesc.value = '';
        fSvcIcono.value = ''; fSvcOrden.value = '0';
        fSvcDest.checked = false; fSvcActivo.checked = true;
        fSvcCat.value = '';
        window.EgAdmin.openModal('modal-svc');
    }

    function abrirEditarSvc(id) {
        const s = findSvc(id);
        if (!s) return;
        tituloSvc.textContent = 'Editar servicio';
        fSvcId.value     = s.id;
        fSvcCat.value    = s.categoria_id      || '';
        fSvcTitulo.value = s.titulo            || '';
        fSvcDesc.value   = s.descripcion_breve || '';
        fSvcIcono.value  = s.icono             || '';
        fSvcOrden.value  = s.orden ?? 0;
        fSvcDest.checked   = !!s.es_destacado;
        fSvcActivo.checked = !!s.activo;
        window.EgAdmin.openModal('modal-svc');
    }

    function abrirVerSvc(id) {
        const s = findSvc(id);
        if (!s) return;
        currentVerSvcId = id;
        document.getElementById('info-titulo').textContent      = s.titulo || '';
        document.getElementById('info-categoria').textContent   = s.categoria_nombre || '';
        document.getElementById('info-desc').textContent        = s.descripcion_breve || '— Sin descripción —';
        document.getElementById('info-icono-clase').textContent = s.icono || '—';
        document.getElementById('info-orden').textContent       = s.orden ?? 0;
        document.getElementById('info-icono-preview').innerHTML = '<i class="fas ' + esc(s.icono || 'fa-cog') + '"></i>';
        document.getElementById('info-destacado').innerHTML = s.es_destacado
            ? '<span class="badge-estado activo">Sí</span>'
            : '<span class="badge-estado inactivo">No</span>';
        document.getElementById('info-activo').innerHTML = s.activo
            ? '<span class="badge-estado activo">Activo</span>'
            : '<span class="badge-estado inactivo">Inactivo</span>';
        window.EgAdmin.openModal('modal-svc-info');
    }

    document.getElementById('btn-editar-desde-info')?.addEventListener('click', function () {
        window.EgAdmin.closeModal('modal-svc-info');
        if (currentVerSvcId) abrirEditarSvc(currentVerSvcId);
    });

    function guardarSvc() {
        if (!fSvcTitulo.value.trim() || !fSvcCat.value) {
            Swal.fire({ title: 'Campos requeridos', text: 'Título y categoría son obligatorios.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fSvcId.value);
        fd.append('categoria_id',fSvcCat.value);
        fd.append('titulo',      fSvcTitulo.value.trim());
        fd.append('descripcion', fSvcDesc.value.trim());
        fd.append('icono',       fSvcIcono.value.trim());
        fd.append('orden',       fSvcOrden.value || '0');
        fd.append('es_destacado',fSvcDest.checked  ? '1' : '0');
        fd.append('activo',      fSvcActivo.checked ? '1' : '0');
        fetch('../api/servicios/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-svc');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminarSvc(id) {
        Swal.fire({
            title: '¿Eliminar servicio?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/servicios/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    /* ══════════════ CRUD CATEGORÍAS ══════════════ */

    function abrirNuevaCat() {
        tituloCat.textContent = 'Nueva categoría';
        fCatId.value = ''; fCatNombre.value = ''; fCatIcono.value = '';
        fCatDesc.value = ''; fCatOrden.value = '0'; fCatActivo.checked = true;
        window.EgAdmin.openModal('modal-cat');
    }

    function abrirEditarCat(id) {
        const c = categorias.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloCat.textContent = 'Editar categoría';
        fCatId.value     = c.id;
        fCatNombre.value = c.nombre            || '';
        fCatIcono.value  = c.icono             || '';
        fCatDesc.value   = c.descripcion_breve || '';
        fCatOrden.value  = c.orden ?? 0;
        fCatActivo.checked = !!c.activo;
        window.EgAdmin.openModal('modal-cat');
    }

    function guardarCat() {
        if (!fCatNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',               fCatId.value);
        fd.append('nombre',           fCatNombre.value.trim());
        fd.append('icono',            fCatIcono.value.trim());
        fd.append('descripcion_breve',fCatDesc.value.trim());
        fd.append('orden',            fCatOrden.value || '0');
        fd.append('activo',           fCatActivo.checked ? '1' : '0');
        fetch('../api/servicios/categorias/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-cat');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    /* ── Event delegation ── */

    grid.addEventListener('click', function (e) {
        const verSvc  = e.target.closest('.ver-svc');
        const editSvc = e.target.closest('.edit-svc');
        const delSvc  = e.target.closest('.del-svc');
        const editCat = e.target.closest('.edit-cat');
        if (verSvc)  abrirVerSvc(verSvc.dataset.id);
        if (editSvc) abrirEditarSvc(editSvc.dataset.id);
        if (delSvc)  eliminarSvc(delSvc.dataset.id);
        if (editCat) abrirEditarCat(editCat.dataset.id);
    });

    btnNuevoSvc?.addEventListener('click', abrirNuevoSvc);
    btnNuevaCat?.addEventListener('click', abrirNuevaCat);
    btnTabSvc?.addEventListener('click', function () { cambiarVista('servicios'); });
    btnTabCat?.addEventListener('click', function () { cambiarVista('categorias'); });
    btnGuardSvc?.addEventListener('click', guardarSvc);
    btnGuardCat?.addEventListener('click', guardarCat);

    cargar();
})();
