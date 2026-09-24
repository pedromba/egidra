/* EGIDRA — Socios dinámico */
(function () {
    'use strict';

    const list       = document.getElementById('list-socios');
    const searchEl   = document.getElementById('search-socio');
    const btnNuevo   = document.getElementById('btn-nuevo-socio');
    const btnGuardar = document.getElementById('btn-guardar-socio');
    const tituloMod  = document.getElementById('modal-socio-title');

    const fId          = document.getElementById('soc-id');
    const fNombre      = document.getElementById('soc-nombre');
    const fDesc        = document.getElementById('soc-desc');
    const fLogo        = document.getElementById('soc-logo');
    const fLogoActual  = document.getElementById('soc-logo-actual');
    const fUrl         = document.getElementById('soc-url');
    const fOrden       = document.getElementById('soc-orden');
    const fActivo      = document.getElementById('soc-activo');

    const logoArea      = document.getElementById('logo-area');
    const logoPreviewW  = document.getElementById('logo-preview-wrap');
    const logoPreview   = document.getElementById('logo-preview');
    const logoLabelTxt  = document.getElementById('logo-label-txt');
    const logoLabel     = document.getElementById('logo-label');
    const btnLogoRemove = document.getElementById('logo-remove');

    const COLORES = ['av-y','av-b','av-g','av-p','av-r'];
    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    /* ── Logo preview ── */
    function mostrarPreview(src) {
        logoPreview.src             = src;
        logoPreviewW.style.display  = '';
        logoLabel.style.display     = 'none';
    }

    function ocultarPreview() {
        logoPreview.src             = '';
        logoPreviewW.style.display  = 'none';
        logoLabel.style.display     = '';
        logoLabelTxt.textContent    = 'Haz clic o arrastra un logo';
        fLogo.value       = '';
        fLogoActual.value = '';
    }

    fLogo?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        logoLabelTxt.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function (e) { mostrarPreview(e.target.result); };
        reader.readAsDataURL(file);
    });

    btnLogoRemove?.addEventListener('click', function (e) {
        e.stopPropagation();
        ocultarPreview();
    });

    logoArea?.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    logoArea?.addEventListener('dragleave', function () { this.classList.remove('drag-over'); });
    logoArea?.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        fLogo.files = dt.files;
        fLogo.dispatchEvent(new Event('change'));
    });

    /* ── Render ── */
    function renderList(lista) {
        if (!lista.length) {
            list.innerHTML = '<div class="p-4 text-center text-muted">No hay socios registrados.</div>';
            return;
        }
        list.innerHTML = lista.map((s, i) => {
            const logoHtml = s.logo
                ? '<img src="' + esc(s.logo) + '" alt="' + esc(s.nombre) + '" class="socio-logo" style="object-fit:contain;padding:6px;background:#fff;border:1px solid var(--border);">'
                : '<div class="socio-logo ' + COLORES[i % COLORES.length] + '">' + esc(s.nombre.substring(0, 2).toUpperCase()) + '</div>';
            return `
            <div class="socio-row">
                ${logoHtml}
                <div class="socio-info">
                    <div class="socio-name">${esc(s.nombre)}</div>
                    <div class="socio-desc">${esc(s.descripcion || '')}</div>
                </div>
                <div class="socio-meta">
                    <span class="badge-pill ${s.activo ? 'bp-green' : 'bp-gray'}">${s.activo ? 'Activo' : 'Inactivo'}</span>
                </div>
                <div class="socio-actions">
                    ${s.url_web ? `<a href="${esc(s.url_web)}" target="_blank" class="btn-icon view" title="Ver web"><i class="fas fa-arrow-up-right-from-square"></i></a>` : ''}
                    <button class="btn-icon edit" data-id="${s.id}" title="Editar"><i class="fas fa-pen"></i></button>
                    <button class="btn-icon del" data-id="${s.id}" title="Eliminar"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    function cargar() {
        list.innerHTML = '<div class="p-4 text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/socios/listar.php')
            .then(r => r.json())
            .then(data => { todos = data.estado ? data.datos : []; renderList(todos); })
            .catch(() => { list.innerHTML = '<div class="p-4 text-center text-muted">Error al cargar.</div>'; });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fDesc.value = '';
        fUrl.value = ''; fOrden.value = '0'; fActivo.checked = true;
        ocultarPreview();
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo socio';
        limpiarModal();
        window.EgAdmin.openModal('modal-socio');
    }

    function abrirEditar(id) {
        const s = todos.find(x => x.id == id);
        if (!s) return;
        tituloMod.textContent = 'Editar socio';
        fId.value      = s.id;
        fNombre.value  = s.nombre      || '';
        fDesc.value    = s.descripcion || '';
        fUrl.value     = s.url_web     || '';
        fOrden.value   = s.orden ?? 0;
        fActivo.checked = !!s.activo;
        ocultarPreview();
        if (s.logo) {
            fLogoActual.value = s.logo;
            mostrarPreview(s.logo);
        }
        window.EgAdmin.openModal('modal-socio');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fId.value);
        fd.append('nombre',      fNombre.value.trim());
        fd.append('descripcion', fDesc.value.trim());
        fd.append('logo_actual', fLogoActual.value);
        fd.append('url_web',     fUrl.value.trim());
        fd.append('orden',       fOrden.value || '0');
        fd.append('activo',      fActivo.checked ? '1' : '0');
        if (fLogo.files[0]) {
            fd.append('logo', fLogo.files[0]);
        }

        fetch('../api/socios/guardar.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-socio');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(() => Swal.fire({ title: 'Error de conexión', icon: 'error' }));
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar socio?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(res => {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/socios/eliminar.php', { method: 'POST', body: fd })
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

    list.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit');
        const del  = e.target.closest('.del');
        if (edit) abrirEditar(edit.dataset.id);
        if (del)  eliminar(del.dataset.id);
    });

    searchEl?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderList(q ? todos.filter(s => s.nombre.toLowerCase().includes(q)) : todos);
    });

    cargar();
})();
