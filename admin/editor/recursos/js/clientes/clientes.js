/* EGIDRA — Clientes Editor (CRUD) */
(function () {
    'use strict';

    const gridEl     = document.getElementById('grid-clientes');
    const statsEl    = document.getElementById('stats-cli');
    const btnNuevo   = document.getElementById('btn-nuevo-cli');
    const btnGuardar = document.getElementById('btn-guardar-cli');
    const tituloMod  = document.getElementById('modal-cli-title');
    const rowActivo  = document.getElementById('row-activo');

    const fId         = document.getElementById('cli-id');
    const fNombre     = document.getElementById('cli-nombre');
    const fIniciales  = document.getElementById('cli-iniciales');
    const fSector     = document.getElementById('cli-sector');
    const fDesc       = document.getElementById('cli-desc');
    const fActivo     = document.getElementById('cli-activo');
    const fLogoActual = document.getElementById('cli-logo-actual');
    const fLogoFile   = document.getElementById('cli-logo-file');
    const logoPreview = document.getElementById('cli-logo-preview');
    const btnLogoSel  = document.getElementById('btn-logo-sel');
    const btnLogoQuit = document.getElementById('btn-logo-quitar');
    let   quitarLogo  = false;

    const AV = ['av-y', 'av-b', 'av-g', 'av-p', 'av-r'];
    let clientes     = [];
    let filtroSearch = '';

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

    function setLogoPreview(url) {
        if (url) {
            logoPreview.innerHTML = '<img src="' + url + '" style="width:100%;height:100%;object-fit:contain;padding:4px;">';
            if (btnLogoQuit) btnLogoQuit.style.display = '';
        } else {
            logoPreview.innerHTML = '<i class="fas fa-image fa-lg" style="color:var(--muted);"></i>';
            if (btnLogoQuit) btnLogoQuit.style.display = 'none';
        }
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' cliente' + (lista.length !== 1 ? 's' : '');
        if (!lista.length) {
            gridEl.innerHTML = '<div class="text-center text-muted py-5">No hay clientes que coincidan.</div>';
            return;
        }
        gridEl.innerHTML =
            '<div class="table-wrap"><table class="cli-table">' +
            '<thead><tr>' +
            '<th>Cliente</th><th>Sector</th>' +
            '<th class="text-center">Estado</th>' +
            '<th class="text-center">Acciones</th>' +
            '</tr></thead><tbody>' +
            lista.map(function (c) {
                const ini    = esc(c.iniciales || autoIniciales(c.nombre));
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
                        '<button class="btn-icon edit-cli" data-id="' + c.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del-cli"  data-id="' + c.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div></td>' +
                '</tr>';
            }).join('') +
            '</tbody></table></div>';
    }

    function toggleEstado(id) {
        const btn = gridEl.querySelector('.btn-toggle-estado[data-id="' + id + '"]');
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
                const c = clientes.find(function (x) { return x.id == id; });
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
        gridEl.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/clientes/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { clientes = data.estado ? data.datos : []; render(); })
            .catch(function () { gridEl.innerHTML = '<div class="text-center text-muted py-4">Error al cargar los clientes.</div>'; });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fIniciales.value = '';
        fSector.value = ''; fDesc.value = ''; fActivo.checked = true;
        fLogoActual.value = ''; if (fLogoFile) fLogoFile.value = '';
        quitarLogo = false;
        setLogoPreview(null);
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Cliente';
        limpiarModal();
        if (rowActivo) rowActivo.style.display = 'none';
        window.EgAdmin.openModal('modal-cli');
    }

    function abrirEditar(id) {
        const c = clientes.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloMod.textContent  = 'Editar Cliente';
        fId.value        = c.id;
        fNombre.value    = c.nombre       || '';
        fIniciales.value = c.iniciales    || '';
        fSector.value    = c.sector       || '';
        fDesc.value      = c.descripcion  || '';
        fActivo.checked  = !!c.activo;
        fLogoActual.value = c.logo_url    || '';
        if (fLogoFile) fLogoFile.value = '';
        quitarLogo = false;
        setLogoPreview(c.logo_url || null);
        if (rowActivo) rowActivo.style.display = '';
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
        if (fLogoFile && fLogoFile.files[0]) fd.append('logo', fLogoFile.files[0]);
        if (quitarLogo) fd.append('quitar_logo', '1');

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

    fNombre?.addEventListener('input', function () {
        if (!fId.value && !fIniciales.value) {
            fIniciales.value = autoIniciales(this.value);
        }
    });

    document.getElementById('search-client')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    gridEl.addEventListener('click', function (e) {
        const editBtn   = e.target.closest('.edit-cli');
        const delBtn    = e.target.closest('.del-cli');
        const toggleBtn = e.target.closest('.btn-toggle-estado');
        if (editBtn)   abrirEditar(editBtn.dataset.id);
        if (delBtn)    eliminar(delBtn.dataset.id);
        if (toggleBtn) toggleEstado(toggleBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
