/* EGIDRA — Socios y Alianzas (CRUD) */
(function () {
    'use strict';

    const listaEl    = document.getElementById('lista-socios');
    const statsEl    = document.getElementById('stats-socios');
    const btnNuevo   = document.getElementById('btn-nuevo-socio');
    const btnGuardar = document.getElementById('btn-guardar-soc');
    const tituloMod  = document.getElementById('modal-socio-title');

    const fId     = document.getElementById('soc-id');
    const fNombre = document.getElementById('soc-nombre');
    const fDesc   = document.getElementById('soc-desc');
    const fLogo   = document.getElementById('soc-logo');
    const fUrl    = document.getElementById('soc-url');
    const fOrden  = document.getElementById('soc-orden');
    const fActivo = document.getElementById('soc-activo');

    let socios       = [];
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

    function filtrados() {
        if (!filtroSearch) return socios;
        return socios.filter(function (s) {
            return (s.nombre + ' ' + (s.descripcion || '')).toLowerCase().includes(filtroSearch);
        });
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' socio' + (lista.length !== 1 ? 's' : '');
        if (!lista.length) {
            listaEl.innerHTML = '<div class="socio-row text-muted">No hay socios que coincidan.</div>';
            return;
        }
        listaEl.innerHTML = lista.map(function (s) {
            const color = avatarColor(s.nombre);
            const logoHtml = s.logo
                ? '<img src="' + esc(s.logo) + '" alt="' + esc(s.nombre) + '" class="socio-logo" style="object-fit:contain;padding:6px;background:#fff;border:1px solid var(--border);">'
                : '<div class="socio-logo ' + color + '">' + autoIniciales(s.nombre) + '</div>';
            return '<div class="socio-row" data-id="' + s.id + '">' +
                logoHtml +
                '<div class="socio-info">' +
                    '<div class="socio-name">' + esc(s.nombre) + '</div>' +
                    '<div class="socio-desc">' + esc(s.descripcion || '') + '</div>' +
                '</div>' +
                '<div class="socio-meta">' +
                    '<span class="badge-pill ' + (s.activo ? 'bp-green' : 'bp-gray') + '">' + (s.activo ? 'Activo' : 'Inactivo') + '</span>' +
                '</div>' +
                '<div class="socio-actions">' +
                    (s.url_web ? '<a href="' + esc(s.url_web) + '" target="_blank" class="btn-icon view" title="Ver web"><i class="fas fa-arrow-up-right-from-square"></i></a>' : '') +
                    '<button class="btn-icon edit edit-soc" data-id="' + s.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del del-soc"   data-id="' + s.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargar() {
        listaEl.innerHTML = '<div class="socio-row"><i class="fas fa-spinner fa-spin text-muted me-2"></i><span class="text-muted">Cargando...</span></div>';
        fetch('../api/socios/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                socios = data.estado ? data.datos : [];
                render();
            })
            .catch(function () {
                listaEl.innerHTML = '<div class="socio-row text-muted">Error al cargar los socios.</div>';
            });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fDesc.value = '';
        fLogo.value = ''; fUrl.value = ''; fOrden.value = '';
        fActivo.checked = true;
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Socio';
        limpiarModal();
        fOrden.value = socios.length + 1;
        window.EgAdmin.openModal('modal-socio');
    }

    function abrirEditar(id) {
        const s = socios.find(function (x) { return x.id == id; });
        if (!s) return;
        tituloMod.textContent = 'Editar Socio';
        fId.value     = s.id;
        fNombre.value = s.nombre      || '';
        fDesc.value   = s.descripcion || '';
        fLogo.value   = s.logo        || '';
        fUrl.value    = s.url_web     || '';
        fOrden.value  = s.orden       ?? '';
        fActivo.checked = !!s.activo;
        window.EgAdmin.openModal('modal-socio');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',      fId.value);
        fd.append('nombre',  fNombre.value.trim());
        fd.append('desc',    fDesc.value.trim());
        fd.append('logo',    fLogo.value.trim());
        fd.append('url_web', fUrl.value.trim());
        fd.append('orden',   fOrden.value || 0);
        fd.append('activo',  fActivo.checked ? '1' : '0');

        fetch('../api/socios/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-socio');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        const s = socios.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar socio?',
            text: (s ? s.nombre : 'Este socio') + ' será eliminado permanentemente.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/socios/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    document.getElementById('search-socio')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    listaEl.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-soc');
        const delBtn  = e.target.closest('.del-soc');
        if (editBtn) abrirEditar(editBtn.dataset.id);
        if (delBtn)  eliminar(delBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
