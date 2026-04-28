/* EGIDRA — Mensajes dinámico */
(function () {
    'use strict';

    // ── Referencias al DOM ────────────────────────────────────────
    const lista      = document.getElementById('msg-list');
    const panelVacio = document.getElementById('msg-empty');
    const contenido  = document.getElementById('msg-content');
    const elAsunto   = document.getElementById('msg-asunto');
    const elMeta     = document.getElementById('msg-meta');
    const elCuerpo   = document.getElementById('msg-cuerpo');
    const btnEliminar = document.getElementById('btn-eliminar');

    let idActivo = null; // ID del mensaje seleccionado

    // ── Escape HTML para evitar XSS ───────────────────────────────
    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    // ── Formatea fecha MySQL a "dd mmm YYYY, HH:MM" ───────────────
    function formatearFecha(str) {
        const f = new Date(str.replace(' ', 'T'));
        return f.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' })
             + ', '
             + f.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
    }

    // ── Renderiza un ítem en la lista lateral ─────────────────────
    function crearItem(m) {
        const div = document.createElement('div');
        div.className  = 'msg-list-item' + (m.leido ? '' : ' unread');
        div.dataset.id = m.id;

        const punto = m.leido
            ? ''
            : '<span class="msg-unread-dot"></span>';

        div.innerHTML = `
            <div class="msg-li-top">
                <span class="msg-li-name">${esc(m.nombre)}${punto}</span>
                <span class="msg-li-date">${formatearFecha(m.fecha_envio)}</span>
            </div>
            <div class="msg-li-sub">${esc(m.asunto) || '(sin asunto)'}</div>`;

        div.addEventListener('click', function () {
            // Quitar activo anterior
            document.querySelectorAll('.msg-list-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            cargarDetalle(m.id, this);
        });

        return div;
    }

    // ── Carga y renderiza la lista completa ───────────────────────
    function cargarLista() {
        fetch('../api/mensajes/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                lista.innerHTML = '';

                if (!data.estado || !data.datos.length) {
                    lista.innerHTML = '<div class="msg-loading" style="color:var(--muted);">No hay mensajes.</div>';
                    return;
                }

                data.datos.forEach(function (m) {
                    lista.appendChild(crearItem(m));
                });
            })
            .catch(function () {
                lista.innerHTML = '<div class="msg-loading" style="color:var(--muted);">Error al cargar mensajes.</div>';
            });
    }

    // ── Carga el detalle de un mensaje y lo marca como leído ──────
    function cargarDetalle(id, itemEl) {
        // Mostrar spinner mientras carga
        panelVacio.style.display  = 'none';
        contenido.style.display   = 'flex';
        elAsunto.textContent      = '';
        elMeta.textContent        = '';
        elCuerpo.innerHTML        = '<span style="color:var(--muted);font-size:.85rem;"><i class="fas fa-spinner fa-spin me-1"></i>Cargando...</span>';
        idActivo = id;

        fetch('../api/mensajes/leer.php?id=' + id)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) {
                    elCuerpo.textContent = data.mensaje || 'Error al cargar el mensaje.';
                    return;
                }

                const m = data.datos;

                elAsunto.textContent = m.asunto || '(sin asunto)';
                elMeta.innerHTML     = `De: <strong>${esc(m.nombre)}</strong> &lt;${esc(m.email)}&gt; &mdash; ${formatearFecha(m.fecha_envio)}`;
                // nl2br en JS: reemplazar saltos de línea por <br>
                elCuerpo.innerHTML   = esc(m.mensaje).replace(/\n/g, '<br>');

                // Quitar punto de no leído del ítem en la lista
                if (itemEl) {
                    itemEl.classList.remove('unread');
                    const punto = itemEl.querySelector('.msg-unread-dot');
                    if (punto) punto.remove();
                }
            })
            .catch(function () {
                elCuerpo.textContent = 'Error de conexión al cargar el mensaje.';
            });
    }

    // ── Eliminar mensaje activo ───────────────────────────────────
    btnEliminar.addEventListener('click', function () {
        if (!idActivo) return;

        Swal.fire({
            title: '¿Eliminar mensaje?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then(function (result) {
            if (!result.isConfirmed) return;

            const fd = new FormData();
            fd.append('id', idActivo);

            fetch('../api/mensajes/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) {
                        Swal.fire({ title: 'Error', text: data.mensaje || 'No se pudo eliminar.', icon: 'error' });
                        return;
                    }

                    // Quitar ítem de la lista
                    const item = lista.querySelector('[data-id="' + idActivo + '"]');
                    if (item) item.remove();

                    // Volver al estado vacío
                    idActivo                 = null;
                    contenido.style.display  = 'none';
                    panelVacio.style.display = 'flex';

                    // Si no quedan mensajes, mostrar aviso
                    if (!lista.querySelector('.msg-list-item')) {
                        lista.innerHTML = '<div class="msg-loading" style="color:var(--muted);">No hay mensajes.</div>';
                    }

                    Swal.fire({ title: 'Eliminado', text: 'El mensaje fue eliminado.', icon: 'success', timer: 2000, showConfirmButton: false });
                })
                .catch(function () {
                    Swal.fire({ title: 'Error de conexión', text: 'No se pudo conectar al servidor.', icon: 'error' });
                });
        });
    });

    // ── Iniciar carga al montar la página ─────────────────────────
    cargarLista();
})();
