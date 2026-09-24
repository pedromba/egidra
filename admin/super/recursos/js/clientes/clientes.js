/* EGIDRA — Clientes Super (CRUD) */
(function () {
    'use strict';

    const grid       = document.getElementById('grid-clientes');
    const searchEl   = document.getElementById('search-client');
    const btnNuevo   = document.getElementById('btn-nuevo-cliente');
    const btnGuardar = document.getElementById('btn-guardar-cliente');
    const tituloMod  = document.getElementById('modal-cliente-title');
    const rowActivo  = document.getElementById('row-activo');

    const fId         = document.getElementById('cl-id');
    const fNombre     = document.getElementById('cl-nombre');
    const fIni        = document.getElementById('cl-ini');
    const fSector     = document.getElementById('cl-sector');
    const fDesc       = document.getElementById('cl-desc');
    const fActivo     = document.getElementById('cl-activo');
    const fLogoActual = document.getElementById('cl-logo-actual');
    const fLogoFile   = document.getElementById('cl-logo-file');
    const logoPreview = document.getElementById('cl-logo-preview');
    const btnLogoSel  = document.getElementById('btn-cl-logo-sel');
    const btnLogoQuit = document.getElementById('btn-cl-logo-quitar');
    let   quitarLogo  = false;

    const AV = ['av-y', 'av-b', 'av-g', 'av-p', 'av-r'];
    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function avatarColor(str) {
        let h = 0;
        for (let i = 0; i < str.length; i++) h = str.charCodeAt(i) + ((h << 5) - h);
        return AV[Math.abs(h) % AV.length];
    }

    function setLogoPreview(url) {
        if (url) {
            logoPreview.innerHTML = '<img src="' + url + '" style="width:100%;height:100%;object-fit:contain;padding:4px;">';
            if (btnLogoQuit) btnLogoQuit.style.display = '';
        } else {
            logoPreview.innerHTML = '<i class="fas fa-image fa-lg" style="color:var(--muted);"></i>';
            if (btnLogoQuit) btnLogoQuit.style.display = 'none';
        }
    }

    function renderGrid(lista) {
        if (!lista.length) {
            grid.innerHTML = '<div class="text-center text-muted py-5">No hay clientes registrados.</div>';
            return;
        }
        grid.innerHTML =
            '<div class="table-wrap"><table class="cli-table">' +
            '<thead><tr>' +
            '<th>Cliente</th><th>Sector</th>' +
            '<th class="text-center">Estado</th>' +
            '<th class="text-center">Acciones</th>' +
            '</tr></thead><tbody>' +
            lista.map(function (c) {
                const ini    = esc((c.iniciales || c.nombre.substring(0, 2)).toUpperCase());
                const color  = avatarColor(c.nombre);
                const avatar = c.logo_url
                    ? '<div class="cli-avatar"><img src="' + esc(c.logo_url) + '" alt="' + esc(c.nombre) + '"></div>'
                    : '<div class="cli-avatar ' + color + '">' + ini + '</div>';
                return '<tr>' +
                    '<td><div class="d-flex align-items-center gap-3">' +
                        avatar +
                        '<div>' +
                            '<div class="cli-name">' + esc(c.nombre) + '</div>' +
                            (c.iniciales ? '<div class="cli-sector">' + esc(c.iniciales) + '</div>' : '') +
                        '</div>' +
                    '</div></td>' +
                    '<td class="cli-sector">' + esc(c.sector || '—') + '</td>' +
                    '<td class="text-center">' +
                        '<button class="btn-toggle-estado ' + (c.activo ? 'activo' : 'inactivo') +
                            '" data-id="' + c.id + '" title="Click para cambiar estado">' +
                            (c.activo ? 'Activo' : 'Inactivo') +
                        '</button>' +
                    '</td>' +
                    '<td><div class="d-flex gap-1 justify-content-center">' +
                        '<button class="btn-icon edit" data-id="' + c.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del"  data-id="' + c.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div></td>' +
                '</tr>';
            }).join('') +
            '</tbody></table></div>';
    }

    function toggleEstado(id) {
        const btn = grid.querySelector('.btn-toggle-estado[data-id="' + id + '"]');
        if (btn) btn.style.opacity = '.35';
        const fd = new FormData();
        fd.append('id', id);
        fetch('../api/clientes/toggle.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) {
                    Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' });
                    if (btn) btn.style.opacity = '';
                    return;
                }
                const c = todos.find(function (x) { return x.id == id; });
                if (c) c.activo = data.activo ? 1 : 0;
                if (btn) {
                    btn.textContent = data.activo ? 'Activo' : 'Inactivo';
                    btn.className   = 'btn-toggle-estado ' + (data.activo ? 'activo' : 'inactivo');
                    btn.style.opacity = '';
                }
            })
            .catch(function () {
                Swal.fire({ title: 'Error de conexión', icon: 'error' });
                if (btn) btn.style.opacity = '';
            });
    }

    function cargar() {
        grid.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/clientes/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { todos = data.estado ? data.datos : []; renderGrid(todos); })
            .catch(function () { grid.innerHTML = '<div class="text-center text-muted py-4">Error al cargar clientes.</div>'; });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fIni.value = '';
        fSector.value = ''; fDesc.value = ''; fActivo.checked = true;
        fLogoActual.value = ''; if (fLogoFile) fLogoFile.value = '';
        quitarLogo = false;
        setLogoPreview(null);
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Cliente';
        limpiarModal();
        if (rowActivo) rowActivo.style.display = 'none';
        window.EgAdmin.openModal('modal-cliente');
    }

    function abrirEditar(id) {
        const c = todos.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloMod.textContent = 'Editar Cliente';
        fId.value       = c.id;
        fNombre.value   = c.nombre      || '';
        fIni.value      = c.iniciales   || '';
        fSector.value   = c.sector      || '';
        fDesc.value     = c.descripcion || '';
        fActivo.checked = !!c.activo;
        fLogoActual.value = c.logo_url  || '';
        if (fLogoFile) fLogoFile.value = '';
        quitarLogo = false;
        setLogoPreview(c.logo_url || null);
        if (rowActivo) rowActivo.style.display = '';
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
        fd.append('descripcion', fDesc.value.trim());
        fd.append('activo',      fActivo.checked ? '1' : '0');
        if (fLogoFile && fLogoFile.files[0]) fd.append('logo', fLogoFile.files[0]);
        if (quitarLogo) fd.append('quitar_logo', '1');

        fetch('../api/clientes/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-cliente');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar cliente?', text: 'Esta acción no se puede deshacer.',
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

    btnLogoSel?.addEventListener('click', function () { fLogoFile?.click(); });
    fLogoFile?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ title: 'Archivo muy grande', text: 'El logo no puede superar 2 MB.', icon: 'warning' });
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) { quitarLogo = false; setLogoPreview(e.target.result); };
        reader.readAsDataURL(file);
    });
    btnLogoQuit?.addEventListener('click', function () {
        quitarLogo = true;
        if (fLogoFile) fLogoFile.value = '';
        setLogoPreview(null);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    grid.addEventListener('click', function (e) {
        const edit   = e.target.closest('.edit');
        const del    = e.target.closest('.del');
        const toggle = e.target.closest('.btn-toggle-estado');
        if (edit)   abrirEditar(edit.dataset.id);
        if (del)    eliminar(del.dataset.id);
        if (toggle) toggleEstado(toggle.dataset.id);
    });

    searchEl?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderGrid(q ? todos.filter(function (c) {
            return c.nombre.toLowerCase().includes(q) || (c.sector || '').toLowerCase().includes(q);
        }) : todos);
    });

    cargar();
})();
