/* EGIDRA Super Admin — Dashboard dinámico */
(function () {
    'use strict';

    // ── Escape HTML para evitar XSS al insertar datos del servidor ──
    function esc(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str ?? ''));
        return div.innerHTML;
    }

    // ── Formatea una fecha MySQL (YYYY-MM-DD HH:MM:SS) a "dd mmm YYYY" ──
    function formatearFecha(fechaStr) {
        const fecha = new Date(fechaStr.replace(' ', 'T'));
        return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    // ── Devuelve texto relativo: "Hace 3 h", "Ayer", fecha completa ──
    function tiempoRelativo(fechaStr) {
        const fecha  = new Date(fechaStr.replace(' ', 'T'));
        const ahora  = new Date();
        const difMin = Math.floor((ahora - fecha) / 60000);
        const difH   = Math.floor(difMin / 60);
        const difD   = Math.floor(difH / 24);
        if (difMin < 60)  return `Hace ${difMin} min`;
        if (difH   < 24)  return `Hace ${difH} h`;
        if (difD   === 1) return 'Ayer';
        return formatearFecha(fechaStr);
    }

    // ── Clase CSS del indicador según el tipo de acción del log ──
    function colorAccion(accion) {
        const mapa = { LOGIN: 'li-b', CREAR: 'li-g', EDITAR: 'li-y', ELIMINAR: 'li-r', SISTEMA: 'li-b' };
        return mapa[accion] ?? 'li-b';
    }

    // ── KPIs: mensajes, proyectos, clientes, usuarios ────────────
    function cargarKpis() {
        fetch('api/dashboard/kpis.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) return;
                const d = data.datos;

                // Mensajes nuevos + trend "X hoy"
                document.getElementById('kpi-mensajes').textContent = d.mensajes_nuevos;
                const trendMsj = document.getElementById('kpi-mensajes-trend');
                if (d.mensajes_hoy > 0) {
                    trendMsj.className   = 'kpi-trend up';
                    trendMsj.innerHTML   = `<i class="fas fa-arrow-up me-1"></i>${d.mensajes_hoy} hoy`;
                } else {
                    trendMsj.className   = 'kpi-trend neu';
                    trendMsj.innerHTML   = '<i class="fas fa-minus me-1"></i>Sin cambios';
                }

                document.getElementById('kpi-proyectos').textContent = d.proyectos;
                document.getElementById('kpi-clientes').textContent  = d.clientes;
                document.getElementById('kpi-usuarios').textContent  = d.usuarios;
            })
            .catch(function () { /* fallo silencioso — los "—" quedan como fallback */ });
    }

    // ── Mensajes recientes ────────────────────────────────────────
    function cargarMensajes() {
        fetch('api/dashboard/mensajes_recientes.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                const tbody = document.getElementById('tbody-mensajes');
                if (!data.estado || !data.datos.length) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Sin mensajes recientes.</td></tr>';
                    return;
                }
                // leido es boolean que viene del servidor
                tbody.innerHTML = data.datos.map(function (m) {
                    const badge = m.leido
                        ? '<span class="badge-pill bp-gray">Leído</span>'
                        : '<span class="badge-pill bp-blue">Nuevo</span>';
                    return `<tr>
                        <td>
                            <div class="msg-sender">${esc(m.nombre)}</div>
                            <div class="msg-email">${esc(m.email)}</div>
                        </td>
                        <td>${esc(m.asunto) || '—'}</td>
                        <td>${badge}</td>
                        <td style="color:var(--muted);font-size:.75rem;">${formatearFecha(m.fecha_envio)}</td>
                        <td><a href="mensajes/" class="btn-icon view"><i class="fas fa-eye"></i></a></td>
                    </tr>`;
                }).join('');
            })
            .catch(function () {
                document.getElementById('tbody-mensajes').innerHTML =
                    '<tr><td colspan="5" class="text-center text-muted py-3">Error al cargar mensajes.</td></tr>';
            });
    }

    // ── Actividad reciente ────────────────────────────────────────
    function cargarActividad() {
        fetch('api/dashboard/actividad_reciente.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                const feed = document.getElementById('feed-actividad');
                if (!data.estado || !data.datos.length) {
                    feed.innerHTML = '<div class="log-row"><span class="text-muted" style="font-size:.83rem;">Sin actividad registrada.</span></div>';
                    return;
                }
                feed.innerHTML = data.datos.map(function (log) {
                    return `<div class="log-row">
                        <div class="log-indicator ${colorAccion(log.accion)}"></div>
                        <div>
                            <div class="lt">${esc(log.descripcion) || esc(log.accion)}</div>
                            <div class="lm">${tiempoRelativo(log.fecha_hora)} &mdash; ${esc(log.usuario)}</div>
                        </div>
                    </div>`;
                }).join('');
            })
            .catch(function () {
                document.getElementById('feed-actividad').innerHTML =
                    '<div class="log-row"><span class="text-muted" style="font-size:.83rem;">Error al cargar actividad.</span></div>';
            });
    }

    // Ejecutar solo si los elementos del dashboard existen en la página
    if (document.getElementById('kpi-mensajes')) {
        cargarKpis();
        cargarMensajes();
        cargarActividad();
    }
})();
