/* EGIDRA — Usuarios dinámico */
(function () {
    'use strict';

    const tbody      = document.getElementById('tbody-usuarios');
    const searchEl   = document.getElementById('search-user');
    const btnNuevo   = document.getElementById('btn-nuevo-usuario');
    const btnGuardar = document.getElementById('btn-guardar-usuario');
    const tituloMod  = document.getElementById('modal-usuario-title');
    const passHint   = document.getElementById('pass-hint');
    const passRow    = document.getElementById('pass-row');
    const passNote   = document.getElementById('pass-auto-note');

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
            const deactBtn = u.estado === 'activo'
                ? '<button class="btn-icon deact" data-id="' + u.id + '" title="Desactivar"><i class="fas fa-power-off"></i></button>'
                : '<button class="btn-icon react" data-id="' + u.id + '" title="Reactivar"><i class="fas fa-power-off" style="color:#059669;"></i></button>';
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
                    '<button class="btn-icon reset-pass" data-id="' + u.id + '" title="Resetear contraseña"><i class="fas fa-key"></i></button>' +
                    deactBtn +
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
        fId.value = ''; fNombre.value = ''; fEmail.value = '';
        fPass.value = ''; fRol.value = 'Editor'; fActivo.checked = true;
        if (passRow)  passRow.style.display  = 'none';
        if (passNote) passNote.style.display  = '';
        window.EgAdmin.openModal('modal-usuario');
    }

    function abrirEditar(id) {
        const u = todos.find(function (x) { return x.id == id; });
        if (!u) return;
        tituloMod.textContent = 'Editar usuario';
        if (passRow)  passRow.style.display  = '';
        if (passNote) passNote.style.display  = 'none';
        if (passHint) passHint.style.display  = '';
        fId.value       = u.id;
        fNombre.value   = u.nombre || '';
        fEmail.value    = u.email  || '';
        fPass.value     = '';
        fRol.value      = u.rol    || 'Editor';
        fActivo.checked = u.estado === 'activo';
        window.EgAdmin.openModal('modal-usuario');
    }

    function setBtnLoading(btn, loading) {
        if (loading) {
            btn.disabled = true;
            btn._origHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
        } else {
            btn.disabled = false;
            btn.innerHTML = btn._origHtml || btn.innerHTML;
        }
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

        setBtnLoading(btnGuardar, true);
        fetch('../api/usuarios/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                setBtnLoading(btnGuardar, false);
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-usuario');
                if (data.mail_ok === false) {
                    Swal.fire({ title: 'Usuario creado', text: data.mensaje, icon: 'warning' });
                } else {
                    Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 2500, showConfirmButton: false });
                }
                cargar();
            })
            .catch(function () {
                setBtnLoading(btnGuardar, false);
                Swal.fire({ title: 'Error de conexión', icon: 'error' });
            });
    }

    function desactivar(id) {
        const u = todos.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Desactivar usuario?',
            text: (u ? u.nombre : 'Este usuario') + ' no podrá acceder al panel.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, desactivar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/usuarios/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Desactivado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    function reactivar(id) {
        const u = todos.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Reactivar usuario?',
            text: (u ? u.nombre : 'Este usuario') + ' podrá volver a acceder al panel.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Sí, reactivar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#059669', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id',     id);
            fd.append('nombre', u ? u.nombre : '');
            fd.append('email',  u ? u.email  : '');
            fd.append('rol',    u ? u.rol    : 'Editor');
            fd.append('estado', 'activo');
            fetch('../api/usuarios/guardar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Reactivado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    function resetear(id) {
        const u = todos.find(function (x) { return x.id == id; });
        Swal.fire({
            title: 'Resetear contraseña',
            text: 'Se generará una nueva contraseña y se enviará por email a ' + (u ? u.email : 'este usuario') + '.',
            icon: 'info', showCancelButton: true,
            confirmButtonText: 'Resetear y enviar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2563eb', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;

            Swal.fire({
                title: 'Enviando...', text: 'Generando contraseña y enviando email.',
                allowOutsideClick: false, allowEscapeKey: false,
                didOpen: function () { Swal.showLoading(); }
            });

            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/usuarios/resetear.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    if (data.mail_ok === false) {
                        Swal.fire({ title: 'Contraseña reseteada', text: data.mensaje, icon: 'warning' });
                    } else {
                        Swal.fire({ title: 'Contraseña reseteada', text: data.mensaje, icon: 'success', timer: 2500, showConfirmButton: false });
                    }
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
        const edit  = e.target.closest('.edit');
        const deact = e.target.closest('.deact');
        const react = e.target.closest('.react');
        const reset = e.target.closest('.reset-pass');
        if (edit)  abrirEditar(edit.dataset.id);
        if (deact) desactivar(deact.dataset.id);
        if (react) reactivar(react.dataset.id);
        if (reset) resetear(reset.dataset.id);
    });

    searchEl?.addEventListener('input', applyFilter);

    cargar();
})();
