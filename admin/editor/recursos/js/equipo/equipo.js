/* EGIDRA — Equipo (CRUD) */
(function () {
    'use strict';

    const gridEl     = document.getElementById('grid-equipo');
    const statsEl    = document.getElementById('stats-equipo');
    const btnNuevo   = document.getElementById('btn-nuevo-miembro');
    const btnGuardar = document.getElementById('btn-guardar-miem');
    const tituloMod  = document.getElementById('modal-miembro-title');

    const fId         = document.getElementById('miem-id');
    const fNombre     = document.getElementById('miem-nombre');
    const fCargo      = document.getElementById('miem-cargo');
    const fBio        = document.getElementById('miem-bio');
    const fFoto       = document.getElementById('miem-foto');
    const fFotoActual = document.getElementById('miem-foto-actual');
    const fLinkedin   = document.getElementById('miem-linkedin');
    const fOrden      = document.getElementById('miem-orden');
    const fActivo     = document.getElementById('miem-activo');

    const fotoArea      = document.getElementById('foto-area');
    const fotoPreviewW  = document.getElementById('foto-preview-wrap');
    const fotoPreview   = document.getElementById('foto-preview');
    const fotoLabelTxt  = document.getElementById('foto-label-txt');
    const fotoLabel     = document.getElementById('foto-label');
    const btnFotoRemove = document.getElementById('foto-remove');

    let equipo       = [];
    let filtroSearch = '';

    const AV_COLORS = ['av-y', 'av-b', 'av-g', 'av-p'];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function avatarColor(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) hash = str.charCodeAt(i) + ((hash << 5) - hash);
        return AV_COLORS[Math.abs(hash) % AV_COLORS.length];
    }

    function autoIniciales(nombre) {
        const words = nombre.trim().split(/\s+/);
        const a = words[0] ? words[0][0] : '';
        const b = words[1] ? words[1][0] : (words[0] ? (words[0][1] || '') : '');
        return (a + b).toUpperCase();
    }

    /* ── Foto preview helpers ── */
    function mostrarPreview(src) {
        fotoPreview.src      = src;
        fotoPreviewW.style.display  = '';
        fotoLabel.style.display     = 'none';
    }

    function ocultarPreview() {
        fotoPreview.src      = '';
        fotoPreviewW.style.display  = 'none';
        fotoLabel.style.display     = '';
        fotoLabelTxt.textContent    = 'Haz clic o arrastra una imagen';
        fFoto.value = '';
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

    /* drag & drop */
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

    /* ── Filtros y render ── */
    function filtrados() {
        if (!filtroSearch) return equipo;
        return equipo.filter(function (m) {
            return (m.nombre + ' ' + (m.cargo || '')).toLowerCase().includes(filtroSearch);
        });
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' miembro' + (lista.length !== 1 ? 's' : '');
        if (!lista.length) {
            gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay miembros que coincidan.</div>';
            return;
        }
        gridEl.innerHTML = lista.map(function (m) {
            const ini   = autoIniciales(m.nombre);
            const color = avatarColor(m.nombre);
            const avatar = m.foto
                ? '<img src="' + esc(m.foto) + '" alt="' + esc(m.nombre) + '" class="team-avatar" style="object-fit:cover;">'
                : '<div class="team-avatar ' + color + '">' + ini + '</div>';
            return '<div class="col-sm-6 col-lg-4 col-xl-3 team-card-wrap" data-id="' + m.id + '">' +
                '<div class="team-card">' +
                    avatar +
                    '<div class="team-name">' + esc(m.nombre) + '</div>' +
                    '<div class="team-cargo">' + esc(m.cargo || '') + '</div>' +
                    '<div class="team-bio">' + esc(m.bio || '') + '</div>' +
                    '<div class="team-foot">' +
                        '<span class="badge-pill ' + (m.activo ? 'bp-green' : 'bp-gray') + '">' + (m.activo ? 'Activo' : 'Inactivo') + '</span>' +
                        '<div class="d-flex gap-1">' +
                            (m.linkedin ? '<a href="' + esc(m.linkedin) + '" target="_blank" class="btn-icon view" title="LinkedIn"><i class="fab fa-linkedin"></i></a>' : '') +
                            '<button class="btn-icon edit edit-miem" data-id="' + m.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                            '<button class="btn-icon del del-miem"   data-id="' + m.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargar() {
        gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/equipo/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                equipo = data.estado ? data.datos : [];
                render();
            })
            .catch(function () {
                gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar el equipo.</div>';
            });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fCargo.value = '';
        fBio.value = ''; fLinkedin.value = '';
        fOrden.value = ''; fActivo.checked = true;
        ocultarPreview();
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Miembro';
        limpiarModal();
        fOrden.value = equipo.length + 1;
        window.EgAdmin.openModal('modal-miembro');
    }

    function abrirEditar(id) {
        const m = equipo.find(function (x) { return x.id == id; });
        if (!m) return;
        tituloMod.textContent = 'Editar Miembro';
        fId.value       = m.id;
        fNombre.value   = m.nombre   || '';
        fCargo.value    = m.cargo    || '';
        fBio.value      = m.bio      || '';
        fLinkedin.value = m.linkedin || '';
        fOrden.value    = m.orden    ?? '';
        fActivo.checked = !!m.activo;
        /* foto actual */
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
        fd.append('cargo',       fCargo.value.trim());
        fd.append('bio',         fBio.value.trim());
        fd.append('foto_actual', fFotoActual.value);
        fd.append('linkedin',    fLinkedin.value.trim());
        fd.append('orden',       fOrden.value || 0);
        fd.append('activo',      fActivo.checked ? '1' : '0');
        if (fFoto.files[0]) {
            fd.append('foto', fFoto.files[0]);
        }

        fetch('../api/equipo/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-miembro');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        const m = equipo.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar miembro?',
            text: (m ? m.nombre : 'Este miembro') + ' será eliminado permanentemente.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/equipo/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    document.getElementById('search-equipo')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    gridEl.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-miem');
        const delBtn  = e.target.closest('.del-miem');
        if (editBtn) abrirEditar(editBtn.dataset.id);
        if (delBtn)  eliminar(delBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
