/* EGIDRA — Usuarios dinámico */
(function () {
    'use strict';

    const tbody      = document.getElementById('tbody-usuarios');
    const searchEl   = document.getElementById('search-user');
    const btnNuevo   = document.getElementById('btn-nuevo-usuario');
    const btnGuardar = document.getElementById('btn-guardar-usuario');
    const tituloMod  = document.getElementById('modal-usuario-title');
    const passHint   = document.getElementById('pass-hint');

    const fId     = document.getElementById('usr-id');
    const fNombre = document.getElementById('usr-nombre');
    const fEmail  = document.getElementById('usr-email');
    const fPass   = document.getElementById('usr-pass');
    const fRol    = document.getElementById('usr-rol');
    const fActivo = document.getElementById('usr-activo');

    const COLORES = ['av-y','av-b','av-g','av-p','av-r'];
    let todos = [];
    let rolActivo = '';

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function formatFecha(f) {
        if (!f) return '—';
        const d = new Date(f);
        return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function applyFilter() {
        const q = searchEl.value.toLowerCase();
        let lista = todos;
        if (rolActivo) lista = lista.filter(function (u) { return u.rol === rolActivo; });
        if (q) lista = lista.filter(function (u) {
            return u.nombre.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
        });
        renderTable(lista);
    }

    function renderTable(lista) {
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay usuarios.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map(function (u, i) {
            const ini = u.nombre.split(' ').map(function (w) { return w[0]; }).join('').substring(0, 2).toUpperCase();
            const rolBadge = u.rol === 'Super'
                ? '<span class="badge-pill role-super">Super</span>'
                : '<span class="badge-pill role-editor">Editor</span>';
            const estadoBadge = u.estado === 'activo'
                ? '<span class="badge-pill bp-green">Activo</span>'
                : '<span class="badge-pill bp-gray">Inactivo</span>';
            return '<tr>' +
                '<td><div class="user-cell">' +
                    '<div class="avatar-ini ' + COLORES[i % COLORES.length] + '">' + esc(ini) + '</div>' +
                    '<div>' +
                        '<div style="font-weight:600;font-size:.82rem;">' + esc(u.nombre) + '</div>' +
                        '<div style="font-size:.72rem;color:var(--muted);">' + esc(u.email) + '</div>' +
                    '</div>' +
                '</div></td>' +
                '<td>' + rolBadge + '</td>' +
                '<td>' + estadoBadge + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);">' + (u.ultimo_acceso ? formatFecha(u.ultimo_acceso) : '—') + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);">' + formatFecha(u.fecha_creacion) + '</td>' +
                '<td><div class="d-flex gap-1">' +
                    '<button class="btn-icon edit" data-id="' + u.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del" data-id="' + u.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div></td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        fetch('../api/usuarios/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { todos = data.estado ? data.datos : []; applyFilter(); })
            .catch(function () { tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Error al cargar.</td></tr>'; });
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo usuario';
        if (passHint) passHint.style.display = 'none';
        fId.value = ''; fNombre.value = ''; fEmail.value = '';
        fPass.value = ''; fRol.value = 'Editor'; fActivo.checked = true;
        window.EgAdmin.openModal('modal-usuario');
    }

    function abrirEditar(id) {
        const u = todos.find(function (x) { return x.id == id; });
        if (!u) return;
        tituloMod.textContent = 'Editar usuario';
        if (passHint) passHint.style.display = '';
        fId.value      = u.id;
        fNombre.value  = u.nombre || '';
        fEmail.value   = u.email  || '';
        fPass.value    = '';
        fRol.value     = u.rol    || 'Editor';
        fActivo.checked= u.estado === 'activo';
        window.EgAdmin.openModal('modal-usuario');
    }

    function guardar() {
        if (!fNombre.value.trim() || !fEmail.value.trim()) {
            Swal.fire({ title: 'Campos requeridos', text: 'Nombre y email son obligatorios.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',     fId.value);
        fd.append('nombre', fNombre.value.trim());
        fd.append('email',  fEmail.value.trim());
        fd.append('pass',   fPass.value);
        fd.append('rol',    fRol.value);
        fd.append('estado', fActivo.checked ? 'activo' : 'inactivo');

        fetch('../api/usuarios/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-usuario');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar usuario?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/usuarios/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    document.querySelectorAll('[data-role-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('[data-role-filter]').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            rolActivo = this.dataset.roleFilter;
            applyFilter();
        });
    });

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    tbody.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit');
        const del  = e.target.closest('.del');
        if (edit) abrirEditar(edit.dataset.id);
        if (del)  eliminar(del.dataset.id);
    });

    searchEl?.addEventListener('input', applyFilter);

    cargar();
})();
