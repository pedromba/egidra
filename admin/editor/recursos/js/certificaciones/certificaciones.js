/* EGIDRA — Certificaciones (CRUD) */
(function () {
    'use strict';

    const tbodyEl    = document.getElementById('tbody-cert');
    const statsEl    = document.getElementById('stats-cert');
    const btnNuevo   = document.getElementById('btn-nueva-cert');
    const btnGuardar = document.getElementById('btn-guardar-cert');
    const tituloMod  = document.getElementById('modal-cert-title');

    const fId         = document.getElementById('cert-id');
    const fNombre     = document.getElementById('cert-nombre');
    const fOrganismo  = document.getElementById('cert-organismo');
    const fAnio       = document.getElementById('cert-anio');
    const fDesc       = document.getElementById('cert-desc');
    const fLogo       = document.getElementById('cert-logo');
    const fUrl        = document.getElementById('cert-url');
    const fVencimiento= document.getElementById('cert-vencimiento');
    const fOrden      = document.getElementById('cert-orden');
    const fEstado     = document.getElementById('cert-estado');

    let certs        = [];
    let filtroSearch = '';

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function estadoBadge(label) {
        if (label === 'vigente') return '<span class="badge-pill cert-vigente">Vigente</span>';
        if (label === 'vence')   return '<span class="badge-pill cert-vence">Por vencer</span>';
        return '<span class="badge-pill cert-vencida">Vencida</span>';
    }

    function fmtFecha(iso) {
        if (!iso) return '<span style="color:var(--muted)">Indefinida</span>';
        const [y, m, d] = iso.split('-');
        return d + '/' + m + '/' + y;
    }

    function filtrados() {
        if (!filtroSearch) return certs;
        return certs.filter(function (c) {
            return (c.nombre + ' ' + (c.organismo_emisor || '')).toLowerCase().includes(filtroSearch);
        });
    }

    function render() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' certificación' + (lista.length !== 1 ? 'es' : '');
        if (!lista.length) {
            tbodyEl.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay certificaciones que coincidan.</td></tr>';
            return;
        }
        tbodyEl.innerHTML = lista.map(function (c) {
            const org2 = (c.organismo_emisor || '--').substring(0, 2).toUpperCase();
            return '<tr data-id="' + c.id + '">' +
                '<td>' +
                    '<div class="cert-name">' + esc(c.nombre) + '</div>' +
                    '<div class="cert-active">' + (c.publicada
                        ? '<span class="badge-pill bp-green">Publicada</span>'
                        : '<span class="badge-pill bp-gray">Oculta</span>') + '</div>' +
                '</td>' +
                '<td>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        '<div class="org-badge">' + esc(org2) + '</div>' +
                        '<span style="font-size:.82rem;">' + esc(c.organismo_emisor || '—') + '</span>' +
                    '</div>' +
                '</td>' +
                '<td style="font-size:.82rem;">' + esc(c.anio_obtencion || '—') + '</td>' +
                '<td style="color:var(--muted);font-size:.8rem;">' + fmtFecha(c.fecha_vencimiento) + '</td>' +
                '<td>' + estadoBadge(c.estado_label) + '</td>' +
                '<td>' +
                    '<div class="d-flex gap-1 justify-content-end">' +
                        (c.url_verificacion ? '<a href="' + esc(c.url_verificacion) + '" target="_blank" class="btn-icon view" title="Verificar"><i class="fas fa-arrow-up-right-from-square"></i></a>' : '') +
                        '<button class="btn-icon edit edit-cert" data-id="' + c.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                        '<button class="btn-icon del del-cert"   data-id="' + c.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                '</td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbodyEl.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        fetch('../api/certificaciones/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                certs = data.estado ? data.datos : [];
                render();
            })
            .catch(function () {
                tbodyEl.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Error al cargar las certificaciones.</td></tr>';
            });
    }

    function limpiarModal() {
        fId.value = ''; fNombre.value = ''; fOrganismo.value = '';
        fAnio.value = ''; fDesc.value = ''; fLogo.value = '';
        fUrl.value = ''; fVencimiento.value = ''; fOrden.value = '';
        fEstado.checked = true;
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nueva Certificación';
        limpiarModal();
        fOrden.value = certs.length + 1;
        window.EgAdmin.openModal('modal-cert');
    }

    function abrirEditar(id) {
        const c = certs.find(function (x) { return x.id == id; });
        if (!c) return;
        tituloMod.textContent   = 'Editar Certificación';
        fId.value               = c.id;
        fNombre.value           = c.nombre            || '';
        fOrganismo.value        = c.organismo_emisor  || '';
        fAnio.value             = c.anio_obtencion    || '';
        fDesc.value             = c.descripcion       || '';
        fLogo.value             = c.logo              || '';
        fUrl.value              = c.url_verificacion  || '';
        fVencimiento.value      = c.fecha_vencimiento || '';
        fOrden.value            = c.orden             ?? '';
        fEstado.checked         = !!c.publicada;
        window.EgAdmin.openModal('modal-cert');
    }

    function guardar() {
        if (!fNombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fId.value);
        fd.append('nombre',      fNombre.value.trim());
        fd.append('organismo',   fOrganismo.value.trim());
        fd.append('anio',        fAnio.value);
        fd.append('desc',        fDesc.value.trim());
        fd.append('logo',        fLogo.value.trim());
        fd.append('url',         fUrl.value.trim());
        fd.append('vencimiento', fVencimiento.value);
        fd.append('orden',       fOrden.value || 0);
        fd.append('estado',      fEstado.checked ? '1' : '0');

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
        const c = certs.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar certificación?',
            text: (c ? '"' + c.nombre + '"' : 'Esta certificación') + ' será eliminada permanentemente.',
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
                    Swal.fire({ title: 'Eliminada', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    document.getElementById('search-cert')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        render();
    });

    tbodyEl.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-cert');
        const delBtn  = e.target.closest('.del-cert');
        if (editBtn) abrirEditar(editBtn.dataset.id);
        if (delBtn)  eliminar(delBtn.dataset.id);
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    cargar();
})();
