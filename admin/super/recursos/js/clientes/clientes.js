/* EGIDRA — Clientes dinámico */
(function () {
    'use strict';

    const grid      = document.getElementById('grid-clientes');
    const searchEl  = document.getElementById('search-client');
    const btnNuevo  = document.getElementById('btn-nuevo-cliente');
    const btnGuardar= document.getElementById('btn-guardar-cliente');
    const tituloMod = document.getElementById('modal-cliente-title');

    const fId     = document.getElementById('cl-id');
    const fNombre = document.getElementById('cl-nombre');
    const fIni    = document.getElementById('cl-ini');
    const fSector = document.getElementById('cl-sector');
    const fLogo   = document.getElementById('cl-logo');
    const fDesc   = document.getElementById('cl-desc');
    const fActivo = document.getElementById('cl-activo');

    const COLORES = ['av-y','av-b','av-g','av-p','av-r'];
    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function renderGrid(lista) {
        if (!lista.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay clientes registrados.</div>';
            return;
        }
        grid.innerHTML = lista.map((c, i) => `
            <div class="col-6 col-md-4 col-lg-3 client-card-wrap">
                <div class="client-card">
                    <div class="client-logo-box ${COLORES[i % COLORES.length]}">${esc((c.iniciales || c.nombre.substring(0,2)).toUpperCase())}</div>
                    <div class="client-name">${esc(c.nombre)}</div>
                    <div class="client-foot">
                        <span class="badge-pill ${c.activo ? 'bp-green' : 'bp-gray'}">${c.activo ? 'Activo' : 'Inactivo'}</span>
                        <div class="d-flex gap-1">
                            <button class="btn-icon edit" data-id="${c.id}" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del" data-id="${c.id}" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>`).join('');
    }

    function cargar() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/clientes/listar.php')
            .then(r => r.json())
            .then(data => { todos = data.estado ? data.datos : []; renderGrid(todos); })
            .catch(() => { grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar clientes.</div>'; });
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Cliente';
        fId.value = ''; fNombre.value = ''; fIni.value = ''; fSector.value = '';
        fLogo.value = ''; fDesc.value = ''; fActivo.checked = true;
        window.EgAdmin.openModal('modal-cliente');
    }

    function abrirEditar(id) {
        const c = todos.find(x => x.id == id);
        if (!c) return;
        tituloMod.textContent = 'Editar Cliente';
        fId.value      = c.id;
        fNombre.value  = c.nombre      || '';
        fIni.value     = c.iniciales   || '';
        fSector.value  = c.sector      || '';
        fLogo.value    = c.logo        || '';
        fDesc.value    = c.descripcion || '';
        fActivo.checked= !!c.activo;
        window.EgAdmin.openModal('modal-cliente');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fId.value);
        fd.append('nombre',      fNombre.value.trim());
        fd.append('iniciales',   fIni.value.trim());
        fd.append('sector',      fSector.value.trim());
        fd.append('logo',        fLogo.value.trim());
        fd.append('descripcion', fDesc.value.trim());
        fd.append('activo',      fActivo.checked ? '1' : '0');

        fetch('../api/clientes/guardar.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-cliente');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(() => Swal.fire({ title: 'Error de conexión', icon: 'error' }));
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar cliente?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(res => {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/clientes/eliminar.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(() => Swal.fire({ title: 'Error de conexión', icon: 'error' }));
        });
    }

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    grid.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit');
        const del  = e.target.closest('.del');
        if (edit) abrirEditar(edit.dataset.id);
        if (del)  eliminar(del.dataset.id);
    });

    searchEl?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderGrid(q ? todos.filter(c => c.nombre.toLowerCase().includes(q) || (c.sector||'').toLowerCase().includes(q)) : todos);
    });

    cargar();
})();
