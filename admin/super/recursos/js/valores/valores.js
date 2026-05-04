/* EGIDRA — Valores corporativos dinámico */
(function () {
    'use strict';

    const grid      = document.getElementById('grid-valores');
    const searchEl  = document.getElementById('search-valor');
    const btnNuevo  = document.getElementById('btn-nuevo-valor');
    const btnGuardar= document.getElementById('btn-guardar-valor');
    const tituloMod = document.getElementById('modal-valor-title');
    const iconoInput= document.getElementById('icono-input');
    const iconoPrev = document.getElementById('icono-preview-i');

    const fId     = document.getElementById('val-id');
    const fTitulo = document.getElementById('val-titulo');
    const fDesc   = document.getElementById('val-desc');
    const fOrden  = document.getElementById('val-orden');
    const fActivo = document.getElementById('val-activo');

    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function renderGrid(lista) {
        if (!lista.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay valores registrados.</div>';
            return;
        }
        grid.innerHTML = lista.map((v, i) => `
            <div class="col-sm-6 col-lg-4 valor-card-wrap">
                <div class="valor-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="valor-icon"><i class="fas ${esc(v.icono || 'fa-star')}"></i></div>
                        <div>
                            <div class="valor-title">${esc(v.titulo)}</div>
                            <span class="badge-pill ${v.activo ? 'bp-green' : 'bp-gray'}">${v.activo ? 'Activo' : 'Inactivo'}</span>
                        </div>
                    </div>
                    <div class="valor-desc">${esc(v.descripcion || '')}</div>
                    <div class="valor-foot">
                        <span class="valor-order">Orden: ${v.orden}</span>
                        <div class="d-flex gap-1">
                            <button class="btn-icon edit" data-id="${v.id}" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del" data-id="${v.id}" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>`).join('');
    }

    function cargar() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('/egidra/admin/super/api/valores/listar.php')
            .then(r => r.json())
            .then(data => { todos = data.estado ? data.datos : []; renderGrid(todos); })
            .catch(() => { grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar.</div>'; });
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo valor';
        fId.value = ''; fTitulo.value = ''; fDesc.value = '';
        iconoInput.value = ''; fOrden.value = '0'; fActivo.checked = true;
        if (iconoPrev) iconoPrev.className = 'fas fa-star';
        window.EgAdmin.openModal('modal-valor');
    }

    function abrirEditar(id) {
        const v = todos.find(x => x.id == id);
        if (!v) return;
        tituloMod.textContent = 'Editar valor';
        fId.value       = v.id;
        fTitulo.value   = v.titulo       || '';
        fDesc.value     = v.descripcion  || '';
        iconoInput.value= v.icono        || '';
        fOrden.value    = v.orden ?? 0;
        fActivo.checked = !!v.activo;
        if (iconoPrev) iconoPrev.className = 'fas ' + (v.icono || 'fa-star');
        window.EgAdmin.openModal('modal-valor');
    }

    iconoInput?.addEventListener('input', function () {
        if (iconoPrev) iconoPrev.className = 'fas ' + (this.value.trim() || 'fa-star');
    });

    function guardar() {
        if (!fTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fId.value);
        fd.append('titulo',      fTitulo.value.trim());
        fd.append('descripcion', fDesc.value.trim());
        fd.append('icono',       iconoInput.value.trim());
        fd.append('orden',       fOrden.value || '0');
        fd.append('activo',      fActivo.checked ? '1' : '0');

        fetch('../api/valores/guardar.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-valor');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(() => Swal.fire({ title: 'Error de conexión', icon: 'error' }));
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar valor?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(res => {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('/egidra/admin/super/api/valores/eliminar.php', { method: 'POST', body: fd })
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
        renderGrid(q ? todos.filter(v => v.titulo.toLowerCase().includes(q)) : todos);
    });

    cargar();
})();
