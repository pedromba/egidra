/* EGIDRA — Equipo dinámico */
(function () {
    'use strict';

    const grid      = document.getElementById('grid-equipo');
    const searchEl  = document.getElementById('search-equipo');
    const btnNuevo  = document.getElementById('btn-nuevo-miembro');
    const btnGuardar= document.getElementById('btn-guardar-miembro');
    const tituloMod = document.getElementById('modal-miembro-title');

    const fId         = document.getElementById('eq-id');
    const fNombre     = document.getElementById('eq-nombre');
    const fIni        = document.getElementById('eq-ini');
    const fCargo      = document.getElementById('eq-cargo');
    const fBio        = document.getElementById('eq-bio');
    const fFoto       = document.getElementById('eq-foto');
    const fFotoActual = document.getElementById('eq-foto-actual');
    const fLinkedin   = document.getElementById('eq-linkedin');
    const fOrden      = document.getElementById('eq-orden');
    const fActivo     = document.getElementById('eq-activo');

    const fotoArea      = document.getElementById('foto-area');
    const fotoPreviewW  = document.getElementById('foto-preview-wrap');
    const fotoPreview   = document.getElementById('foto-preview');
    const fotoLabelTxt  = document.getElementById('foto-label-txt');
    const fotoLabel     = document.getElementById('foto-label');
    const btnFotoRemove = document.getElementById('foto-remove');

    const COLORES = ['av-y','av-b','av-g','av-p','av-r'];
    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    /* ── Foto preview ── */
    function mostrarPreview(src) {
        fotoPreview.src             = src;
        fotoPreviewW.style.display  = '';
        fotoLabel.style.display     = 'none';
    }

    function ocultarPreview() {
        fotoPreview.src             = '';
        fotoPreviewW.style.display  = 'none';
        fotoLabel.style.display     = '';
        fotoLabelTxt.textContent    = 'Haz clic o arrastra una imagen';
        fFoto.value       = '';
        fFotoActual.value = '';
    }

    fFoto?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        fotoLabelTxt.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function (e) { mostrarPreview(e.target.result); };
        reader.readAsDataURL(file);
    });

    btnFotoRemove?.addEventListener('click', function (e) {
        e.stopPropagation();
        ocultarPreview();
    });

    fotoArea?.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    fotoArea?.addEventListener('dragleave', function () { this.classList.remove('drag-over'); });
    fotoArea?.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        fFoto.files = dt.files;
        fFoto.dispatchEvent(new Event('change'));
    });

    fotoLabel?.addEventListener('click', function () { fFoto.click(); });

    /* ── Grid ── */
    function renderGrid(lista) {
        if (!lista.length) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay miembros registrados.</div>';
            return;
        }
        grid.innerHTML = lista.map((m, i) => {
            const ini    = (m.iniciales || m.nombre.substring(0,2)).toUpperCase();
            const color  = COLORES[i % COLORES.length];
            const avatar = m.foto
                ? '<img src="' + esc(m.foto) + '" alt="' + esc(m.nombre) + '" class="team-avatar" style="object-fit:cover;">'
                : '<div class="team-avatar ' + color + '">' + esc(ini) + '</div>';
            return `
            <div class="col-sm-6 col-lg-4 col-xl-3 team-card-wrap">
                <div class="team-card">
                    ${avatar}
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
            </div>`;
        }).join('');
    }

    function cargar() {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/equipo/listar.php')
            .then(r => r.json())
            .then(data => { todos = data.estado ? data.datos : []; renderGrid(todos); })
            .catch(() => { grid.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar.</div>'; });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fIni.value = ''; fCargo.value = '';
        fBio.value = ''; fLinkedin.value = ''; fOrden.value = '0'; fActivo.checked = true;
        ocultarPreview();
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo miembro';
        limpiarModal();
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
        fLinkedin.value = m.linkedin || '';
        fOrden.value    = m.orden ?? 0;
        fActivo.checked = !!m.activo;
        ocultarPreview();
        if (m.foto) {
            fFotoActual.value = m.foto;
            mostrarPreview(m.foto);
        }
        window.EgAdmin.openModal('modal-miembro');
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
        fd.append('cargo',       fCargo.value.trim());
        fd.append('bio',         fBio.value.trim());
        fd.append('foto_actual', fFotoActual.value);
        fd.append('linkedin',    fLinkedin.value.trim());
        fd.append('orden',       fOrden.value || '0');
        fd.append('activo',      fActivo.checked ? '1' : '0');
        if (fFoto.files[0]) {
            fd.append('foto', fFoto.files[0]);
        }

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
