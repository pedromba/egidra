/* EGIDRA — Equipo dinámico */
(function () {
    'use strict';

    const grid      = document.getElementById('grid-equipo');
    const searchEl  = document.getElementById('search-equipo');
    const btnNuevo  = document.getElementById('btn-nuevo-miembro');
    const btnGuardar= document.getElementById('btn-guardar-miembro');
    const tituloMod = document.getElementById('modal-miembro-title');

    const fId      = document.getElementById('eq-id');
    const fNombre  = document.getElementById('eq-nombre');
    const fIni     = document.getElementById('eq-ini');
    const fCargo   = document.getElementById('eq-cargo');
    const fBio     = document.getElementById('eq-bio');
    const fFoto    = document.getElementById('eq-foto');
    const fLinkedin= document.getElementById('eq-linkedin');
    const fOrden   = document.getElementById('eq-orden');
    const fActivo  = document.getElementById('eq-activo');

    const COLORES = ['av-y','av-b','av-g','av-p','av-r'];
    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function renderGrid(lista) {
        if (!lista.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay miembros registrados.</div>';
            return;
        }
        grid.innerHTML = lista.map((m, i) => `
            <div class="col-sm-6 col-lg-4 col-xl-3 team-card-wrap">
                <div class="team-card">
                    <div class="team-avatar ${COLORES[i % COLORES.length]}">${esc((m.iniciales || m.nombre.substring(0,2)).toUpperCase())}</div>
                    <div class="team-name">${esc(m.nombre)}</div>
                    <div class="team-cargo">${esc(m.cargo || '')}</div>
                    <div class="team-bio">${esc(m.bio || '')}</div>
                    <div class="team-foot">
                        <span class="badge-pill ${m.activo ? 'bp-green' : 'bp-gray'}">${m.activo ? 'Activo' : 'Inactivo'}</span>
                        <div class="d-flex gap-1">
                            ${m.linkedin ? `<a href="${esc(m.linkedin)}" target="_blank" class="btn-icon view" title="LinkedIn"><i class="fab fa-linkedin"></i></a>` : ''}
                            <button class="btn-icon edit" data-id="${m.id}" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="btn-icon del" data-id="${m.id}" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>`).join('');
    }

    function cargar() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/equipo/listar.php')
            .then(r => r.json())
            .then(data => { todos = data.estado ? data.datos : []; renderGrid(todos); })
            .catch(() => { grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar.</div>'; });
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo miembro';
        fId.value = ''; fNombre.value = ''; fIni.value = ''; fCargo.value = '';
        fBio.value = ''; fFoto.value = ''; fLinkedin.value = ''; fOrden.value = '0'; fActivo.checked = true;
        window.EgAdmin.openModal('modal-miembro');
    }

    function abrirEditar(id) {
        const m = todos.find(x => x.id == id);
        if (!m) return;
        tituloMod.textContent = 'Editar miembro';
        fId.value       = m.id;
        fNombre.value   = m.nombre   || '';
        fIni.value      = m.iniciales|| '';
        fCargo.value    = m.cargo    || '';
        fBio.value      = m.bio      || '';
        fFoto.value     = m.foto     || '';
        fLinkedin.value = m.linkedin || '';
        fOrden.value    = m.orden ?? 0;
        fActivo.checked = !!m.activo;
        window.EgAdmin.openModal('modal-miembro');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',       fId.value);
        fd.append('nombre',   fNombre.value.trim());
        fd.append('iniciales',fIni.value.trim());
        fd.append('cargo',    fCargo.value.trim());
        fd.append('bio',      fBio.value.trim());
        fd.append('foto',     fFoto.value.trim());
        fd.append('linkedin', fLinkedin.value.trim());
        fd.append('orden',    fOrden.value || '0');
        fd.append('activo',   fActivo.checked ? '1' : '0');

        fetch('../api/equipo/guardar.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-miembro');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(() => Swal.fire({ title: 'Error de conexión', icon: 'error' }));
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar miembro?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(res => {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/equipo/eliminar.php', { method: 'POST', body: fd })
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
        renderGrid(q ? todos.filter(m => m.nombre.toLowerCase().includes(q) || (m.cargo||'').toLowerCase().includes(q)) : todos);
    });

    cargar();
})();
