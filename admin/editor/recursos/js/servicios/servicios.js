/* EGIDRA — Servicios Editor (CRUD servicios, categorías de solo lectura) */
(function () {
    'use strict';

    const grid        = document.getElementById('grid-servicios');
    const statsEl     = document.getElementById('stats-servicios');
    const btnNuevoSvc = document.getElementById('btn-nuevo-svc');
    const btnGuardSvc = document.getElementById('btn-guardar-svc');
    const tituloSvc   = document.getElementById('modal-svc-title');

    const fSvcId     = document.getElementById('svc-id');
    const fSvcCat    = document.getElementById('svc-cat');
    const fSvcTitulo = document.getElementById('svc-titulo');
    const fSvcDesc   = document.getElementById('svc-desc');
    const fSvcIcono  = document.getElementById('svc-icono');
    const fSvcOrden  = document.getElementById('svc-orden');
    const fSvcDest   = document.getElementById('svc-dest');
    const fSvcActivo = document.getElementById('svc-activo');

    let categorias = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function poblarSelectCat() {
        fSvcCat.innerHTML = '<option value="">— Seleccionar —</option>';
        categorias.forEach(function (c) {
            fSvcCat.insertAdjacentHTML('beforeend',
                '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');
        });
    }

    function renderGrid(cats) {
        if (!cats.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay categorías de servicios.</div>';
            if (statsEl) statsEl.textContent = '0 categorías · 0 servicios';
            return;
        }

        let totalSvcs = 0;
        cats.forEach(function (c) { totalSvcs += (c.servicios || []).length; });
        if (statsEl) statsEl.textContent = cats.length + ' categorías · ' + totalSvcs + ' servicios';

        grid.innerHTML = cats.map(function (c) {
            const svcs  = c.servicios || [];
            const items = svcs.map(function (s) {
                return '<div class="svc-item">' +
                    '<span>' + esc(s.titulo) +
                        (s.es_destacado ? ' <i class="fas fa-star" style="color:var(--accent);font-size:.7rem;"></i>' : '') +
                    '</span>' +
                    '<div class="d-flex gap-1">' +
                        '<button class="btn-icon edit-svc" data-id="' + s.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del-svc"  data-id="' + s.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                '</div>';
            }).join('');

            return '<div class="col-md-6">' +
                '<div class="svc-card">' +
                    '<div class="svc-card-head">' +
                        '<div class="svc-icon"><i class="fas ' + esc(c.icono || 'fa-folder') + '"></i></div>' +
                        '<h6>' + esc(c.nombre) + '</h6>' +
                    '</div>' +
                    items +
                    '<div class="svc-item" style="border-top:1px dashed var(--border);margin-top:4px;">' +
                        '<button class="btn-icon add-svc-to-cat" data-cat-id="' + c.id + '"' +
                            ' style="width:100%;justify-content:center;gap:6px;font-size:.75rem;" title="Añadir servicio a esta categoría">' +
                            '<i class="fas fa-plus"></i> Añadir servicio' +
                        '</button>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargar() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/servicios/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                categorias = data.estado ? data.datos : [];
                poblarSelectCat();
                renderGrid(categorias);
            })
            .catch(function () {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar los servicios.</div>';
            });
    }

    function findSvc(id) {
        for (var i = 0; i < categorias.length; i++) {
            var found = (categorias[i].servicios || []).find(function (s) { return s.id == id; });
            if (found) {
                found.categoria_id = categorias[i].id;
                return found;
            }
        }
        return null;
    }

    function abrirNuevoSvc(catId) {
        tituloSvc.textContent = 'Nuevo servicio';
        fSvcId.value = ''; fSvcTitulo.value = ''; fSvcDesc.value = '';
        fSvcIcono.value = ''; fSvcOrden.value = '0';
        fSvcDest.checked = false; fSvcActivo.checked = true;
        fSvcCat.value = catId || '';
        window.EgAdmin.openModal('modal-svc');
    }

    function abrirEditarSvc(id) {
        const s = findSvc(id);
        if (!s) return;
        tituloSvc.textContent  = 'Editar servicio';
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

    function guardarSvc() {
        if (!fSvcTitulo.value.trim() || !fSvcCat.value) {
            Swal.fire({ title: 'Campos requeridos', text: 'Título y categoría son obligatorios.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',           fSvcId.value);
        fd.append('categoria_id', fSvcCat.value);
        fd.append('titulo',       fSvcTitulo.value.trim());
        fd.append('descripcion',  fSvcDesc.value.trim());
        fd.append('icono',        fSvcIcono.value.trim());
        fd.append('orden',        fSvcOrden.value || '0');
        fd.append('es_destacado', fSvcDest.checked  ? '1' : '0');
        fd.append('activo',       fSvcActivo.checked ? '1' : '0');

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

    /* ── Event delegation ── */

    grid.addEventListener('click', function (e) {
        const editSvc = e.target.closest('.edit-svc');
        const delSvc  = e.target.closest('.del-svc');
        const addSvc  = e.target.closest('.add-svc-to-cat');
        if (editSvc) abrirEditarSvc(editSvc.dataset.id);
        if (delSvc)  eliminarSvc(delSvc.dataset.id);
        if (addSvc)  abrirNuevoSvc(addSvc.dataset.catId);
    });

    btnNuevoSvc?.addEventListener('click', function () { abrirNuevoSvc(''); });
    btnGuardSvc?.addEventListener('click', guardarSvc);

    cargar();
})();
