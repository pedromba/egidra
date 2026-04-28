/* EGIDRA — Certificaciones dinámico */
(function () {
    'use strict';

    const tbody      = document.getElementById('tbody-certs');
    const searchEl   = document.getElementById('search-cert');
    const btnNueva   = document.getElementById('btn-nueva-cert');
    const btnGuardar = document.getElementById('btn-guardar-cert');
    const tituloMod  = document.getElementById('modal-cert-title');

    const fId     = document.getElementById('cert-id');
    const fNombre = document.getElementById('cert-nombre');
    const fOrg    = document.getElementById('cert-org');
    const fAnio   = document.getElementById('cert-anio');
    const fDesc   = document.getElementById('cert-desc');
    const fLogo   = document.getElementById('cert-logo');
    const fUrl    = document.getElementById('cert-url');
    const fVence  = document.getElementById('cert-vence');
    const fOrden  = document.getElementById('cert-orden');
    const fActivo = document.getElementById('cert-activo');

    let todos = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function badgeHTML(badge) {
        const map = {
            vigente:    '<span class="badge-pill cert-vigente">Vigente</span>',
            por_vencer: '<span class="badge-pill cert-vence">Por vencer</span>',
            vencida:    '<span class="badge-pill cert-vencida">Vencida</span>',
            indefinida: '<span class="badge-pill bp-gray">Indefinida</span>'
        };
        return map[badge] || '<span class="badge-pill bp-gray">—</span>';
    }

    function formatFecha(f) {
        if (!f) return '<span style="color:var(--muted)">Indefinida</span>';
        const d = new Date(f + 'T00:00:00');
        return d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function renderTable(lista) {
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay certificaciones registradas.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map(function (c) {
            return '<tr>' +
                '<td>' +
                    '<div class="cert-name">' + esc(c.nombre) + '</div>' +
                    '<div class="cert-active">' + (c.publicada ? '<span class="badge-pill bp-green">Publicada</span>' : '<span class="badge-pill bp-gray">Oculta</span>') + '</div>' +
                '</td>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="org-badge">' + esc((c.organismo_emisor || '??').substring(0,2).toUpperCase()) + '</div>' +
                        '<span>' + esc(c.organismo_emisor || '—') + '</span>' +
                    '</div>' +
                '</td>' +
                '<td>' + esc(c.anio_obtencion || '—') + '</td>' +
                '<td style="color:var(--muted);font-size:.8rem;">' + formatFecha(c.fecha_vencimiento) + '</td>' +
                '<td>' + badgeHTML(c.badge) + '</td>' +
                '<td>' +
                    '<div class="d-flex gap-1 justify-content-end">' +
                        '<button class="btn-icon edit" data-id="' + c.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del" data-id="' + c.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                '</td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        fetch('../api/certificaciones/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { todos = data.estado ? data.datos : []; renderTable(todos); })
            .catch(function () { tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Error al cargar.</td></tr>'; });
    }

    function abrirNueva() {
        tituloMod.textContent = 'Nueva certificación';
        fId.value = ''; fNombre.value = ''; fOrg.value = ''; fAnio.value = '';
        fDesc.value = ''; fLogo.value = ''; fUrl.value = ''; fVence.value = '';
        fOrden.value = '0'; fActivo.checked = true;
        window.EgAdmin.openModal('modal-cert');
    }

    function abrirEditar(id) {
        const c = todos.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloMod.textContent = 'Editar certificación';
        fId.value     = c.id;
        fNombre.value = c.nombre            || '';
        fOrg.value    = c.organismo_emisor  || '';
        fAnio.value   = c.anio_obtencion    || '';
        fDesc.value   = c.descripcion       || '';
        fLogo.value   = c.logo              || '';
        fUrl.value    = c.url_verificacion  || '';
        fVence.value  = c.fecha_vencimiento || '';
        fOrden.value  = c.orden ?? 0;
        fActivo.checked = !!c.publicada;
        window.EgAdmin.openModal('modal-cert');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',               fId.value);
        fd.append('nombre',           fNombre.value.trim());
        fd.append('organismo_emisor', fOrg.value.trim());
        fd.append('anio_obtencion',   fAnio.value || '');
        fd.append('descripcion',      fDesc.value.trim());
        fd.append('logo',             fLogo.value.trim());
        fd.append('url_verificacion', fUrl.value.trim());
        fd.append('fecha_vencimiento',fVence.value);
        fd.append('publicada',        fActivo.checked ? '1' : '0');
        fd.append('orden',            fOrden.value || '0');

        fetch('../api/certificaciones/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-cert');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar certificación?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/certificaciones/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    btnNueva?.addEventListener('click', abrirNueva);
    btnGuardar?.addEventListener('click', guardar);

    tbody.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit');
        const del  = e.target.closest('.del');
        if (edit) abrirEditar(edit.dataset.id);
        if (del)  eliminar(del.dataset.id);
    });

    searchEl?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        renderTable(q ? todos.filter(function (c) {
            return c.nombre.toLowerCase().includes(q) ||
                (c.organismo_emisor || '').toLowerCase().includes(q);
        }) : todos);
    });

    cargar();
})();
