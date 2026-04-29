/* EGIDRA — Clientes Editor (CRUD) */
(function () {
    'use strict';

    const gridEl     = document.getElementById('grid-clientes');
    const statsEl    = document.getElementById('stats-cli');
    const btnNuevo   = document.getElementById('btn-nuevo-cli');
    const btnGuardar = document.getElementById('btn-guardar-cli');
    const tituloMod  = document.getElementById('modal-cli-title');

    const fId        = document.getElementById('cli-id');
    const fNombre    = document.getElementById('cli-nombre');
    const fIniciales = document.getElementById('cli-iniciales');
    const fSector    = document.getElementById('cli-sector');
    const fDesc      = document.getElementById('cli-desc');
    const fActivo    = document.getElementById('cli-activo');

    let clientes    = [];
    let filtroSearch = '';

    const AV_COLORS = ['av-y', 'av-b', 'av-g', 'av-p', 'av-r'];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function avatarColor(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return AV_COLORS[Math.abs(hash) % AV_COLORS.length];
    }

    function autoIniciales(nombre) {
        const words = nombre.trim().split(/\s+/);
        const a = words[0] ? words[0][0] : '';
        const b = words[1] ? words[1][0] : (words[0] ? words[0][1] || '' : '');
        return (a + b).toUpperCase();
    }

    function filtrados() {
        if (!filtroSearch) return clientes;
        return clientes.filter(function (c) {
            return (c.nombre + ' ' + (c.sector || '') + ' ' + (c.iniciales || ''))
                .toLowerCase().includes(filtroSearch);
        });
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' cliente' + (lista.length !== 1 ? 's' : '');
        if (!lista.length) {
            gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay clientes que coincidan.</div>';
            return;
        }
        gridEl.innerHTML = lista.map(function (c) {
            const ini   = esc(c.iniciales || autoIniciales(c.nombre));
            const color = avatarColor(c.nombre);
            return '<div class="col-6 col-md-4 col-lg-3 client-card-wrap" data-id="' + c.id + '">' +
                '<div class="client-card">' +
                    '<div class="client-logo-box ' + color + '">' + ini + '</div>' +
                    '<div class="client-name">' + esc(c.nombre) + '</div>' +
                    (c.sector ? '<div style="font-size:.72rem;color:var(--muted);">' + esc(c.sector) + '</div>' : '') +
                    '<div class="client-foot">' +
                        '<span class="badge-pill ' + (c.activo ? 'bp-green' : 'bp-gray') + '">' + (c.activo ? 'Activo' : 'Inactivo') + '</span>' +
                        '<div class="d-flex gap-1">' +
                            '<button class="btn-icon edit edit-cli" data-id="' + c.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                            '<button class="btn-icon del del-cli"   data-id="' + c.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargar() {
        gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/clientes/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                clientes = data.estado ? data.datos : [];
                render();
            })
            .catch(function () {
                gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar los clientes.</div>';
            });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fIniciales.value = '';
        fSector.value = ''; fDesc.value = ''; fActivo.checked = true;
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Cliente';
        limpiarModal();
        window.EgAdmin.openModal('modal-cli');
    }

    function abrirEditar(id) {
        const c = clientes.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloMod.textContent = 'Editar Cliente';
        fId.value        = c.id;
        fNombre.value    = c.nombre       || '';
        fIniciales.value = c.iniciales    || '';
        fSector.value    = c.sector       || '';
        fDesc.value      = c.descripcion  || '';
        fActivo.checked  = !!c.activo;
        window.EgAdmin.openModal('modal-cli');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',        fId.value);
        fd.append('nombre',    fNombre.value.trim());
        fd.append('iniciales', fIniciales.value.trim() || autoIniciales(fNombre.value));
        fd.append('sector',    fSector.value.trim());
        fd.append('desc',      fDesc.value.trim());
        fd.append('activo',    fActivo.checked ? '1' : '0');

        fetch('../api/clientes/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-cli');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        const c = clientes.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar cliente?',
            text: (c ? c.nombre : 'Este cliente') + ' será eliminado permanentemente.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/clientes/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    /* auto-fill iniciales from nombre */
    fNombre?.addEventListener('input', function () {
        if (!fId.value && !fIniciales.value) {
            fIniciales.value = autoIniciales(this.value);
        }
    });

    /* search */
    document.getElementById('search-client')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    /* event delegation on grid */
    gridEl.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-cli');
        const delBtn  = e.target.closest('.del-cli');
        if (editBtn) abrirEditar(editBtn.dataset.id);
        if (delBtn)  eliminar(delBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
