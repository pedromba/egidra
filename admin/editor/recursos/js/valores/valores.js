/* EGIDRA — Valores Corporativos (CRUD) */
(function () {
    'use strict';

    const gridEl     = document.getElementById('grid-valores');
    const statsEl    = document.getElementById('stats-val');
    const btnNuevo   = document.getElementById('btn-nuevo-val');
    const btnGuardar = document.getElementById('btn-guardar-val');
    const tituloMod  = document.getElementById('modal-val-title');

    const fId     = document.getElementById('val-id');
    const fTitulo = document.getElementById('val-titulo');
    const fDesc   = document.getElementById('val-desc');
    const fIcono  = document.getElementById('val-icono');
    const fOrden  = document.getElementById('val-orden');
    const fActivo = document.getElementById('val-activo');
    const fPrev   = document.getElementById('val-icono-prev');

    let valores      = [];
    let filtroSearch = '';

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function filtrados() {
        if (!filtroSearch) return valores;
        return valores.filter(function (v) {
            return (v.titulo + ' ' + (v.descripcion || '')).toLowerCase().includes(filtroSearch);
        });
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' valor' + (lista.length !== 1 ? 'es' : '');
        if (!lista.length) {
            gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay valores que coincidan.</div>';
            return;
        }
        gridEl.innerHTML = lista.map(function (v, i) {
            const icono = v.icono || 'fa-star';
            return '<div class="col-sm-6 col-lg-4 valor-card-wrap" data-id="' + v.id + '">' +
                '<div class="valor-card">' +
                    '<div class="d-flex align-items-center gap-3">' +
                        '<div class="valor-icon"><i class="fas ' + esc(icono) + '"></i></div>' +
                        '<div>' +
                            '<div class="valor-title">' + esc(v.titulo) + '</div>' +
                            '<span class="badge-pill ' + (v.activo ? 'bp-green' : 'bp-gray') + '">' + (v.activo ? 'Activo' : 'Inactivo') + '</span>' +
                        '</div>' +
                    '</div>' +
                    '<div class="valor-desc">' + esc(v.descripcion || '') + '</div>' +
                    '<div class="valor-foot">' +
                        '<span class="valor-order">Orden: ' + (v.orden ?? (i + 1)) + '</span>' +
                        '<div class="d-flex gap-1">' +
                            '<button class="btn-icon edit edit-val" data-id="' + v.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                            '<button class="btn-icon del del-val"   data-id="' + v.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargar() {
        gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/valores/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                valores = data.estado ? data.datos : [];
                render();
            })
            .catch(function () {
                gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar los valores.</div>';
            });
    }

    function limpiarModal() {
        fId.value = ''; fTitulo.value = ''; fDesc.value = '';
        fIcono.value = ''; fOrden.value = ''; fActivo.checked = true;
        if (fPrev) fPrev.className = 'fas fa-star';
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Valor';
        limpiarModal();
        fOrden.value = valores.length + 1;
        window.EgAdmin.openModal('modal-valor');
    }

    function abrirEditar(id) {
        const v = valores.find(function (x) { return x.id == id; });
        if (!v) return;
        tituloMod.textContent = 'Editar Valor';
        fId.value     = v.id;
        fTitulo.value = v.titulo       || '';
        fDesc.value   = v.descripcion  || '';
        fIcono.value  = v.icono        || '';
        fOrden.value  = v.orden        ?? '';
        fActivo.checked = !!v.activo;
        if (fPrev) fPrev.className = 'fas ' + (v.icono || 'fa-star');
        window.EgAdmin.openModal('modal-valor');
    }

    function guardar() {
        if (!fTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',     fId.value);
        fd.append('titulo', fTitulo.value.trim());
        fd.append('desc',   fDesc.value.trim());
        fd.append('icono',  fIcono.value.trim());
        fd.append('orden',  fOrden.value || 0);
        fd.append('activo', fActivo.checked ? '1' : '0');

        fetch('../api/valores/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-valor');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        const v = valores.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar valor?',
            text: (v ? '"' + v.titulo + '"' : 'Este valor') + ' será eliminado permanentemente.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/valores/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    /* preview del icono en tiempo real */
    fIcono?.addEventListener('input', function () {
        if (fPrev) fPrev.className = 'fas ' + (this.value.trim() || 'fa-star');
    });

    document.getElementById('search-valor')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    gridEl.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-val');
        const delBtn  = e.target.closest('.del-val');
        if (editBtn) abrirEditar(editBtn.dataset.id);
        if (delBtn)  eliminar(delBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
