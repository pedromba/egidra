/* EGIDRA — Logs de Actividad */
(function () {
    'use strict';

    const tbody    = document.getElementById('tbody-logs');
    const searchEl = document.getElementById('search-log');

    const BADGES = {
        LOGIN:   'bp-green',
        CREAR:   'bp-blue',
        EDITAR:  'bp-yellow',
        ELIMINAR:'bp-red',
        SISTEMA: 'bp-gray'
    };

    let accionActiva  = '';
    let busquedaActual = '';
    let searchTimer   = null;
    let logsCache     = {};  // id → objeto log completo

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function formatFecha(f) {
        if (!f) return '—';
        const d = new Date(f);
        return d.toLocaleString('es-ES', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }

    function renderTable(lista) {
        logsCache = {};
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No hay registros.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map(function (l) {
            logsCache[l.id] = l;
            const badge = BADGES[l.accion] || 'bp-gray';
            return '<tr>' +
                '<td><span class="badge-pill ' + badge + '">' + esc(l.accion) + '</span></td>' +
                '<td style="font-size:.8rem;font-weight:600;">' + esc(l.usuario) + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);">' + esc(l.tabla || '—') + '</td>' +
                '<td style="font-size:.8rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="' + esc(l.descripcion) + '">' + esc(l.descripcion || '—') + '</td>' +
                '<td style="font-size:.72rem;color:var(--muted);font-family:monospace;">' + esc(l.ip || '—') + '</td>' +
                '<td style="font-size:.75rem;color:var(--muted);white-space:nowrap;">' + formatFecha(l.fecha_hora) + '</td>' +
                '<td><button class="btn-log-detalle" title="Ver detalle" data-log-id="' + l.id + '"><i class="fas fa-eye"></i></button></td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        let url = '../api/logs/listar.php';
        const params = [];
        if (accionActiva)   params.push('accion=' + encodeURIComponent(accionActiva));
        if (busquedaActual) params.push('q='      + encodeURIComponent(busquedaActual));
        if (params.length)  url += '?' + params.join('&');

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) { renderTable(data.estado ? data.datos : []); })
            .catch(function () {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Error al cargar.</td></tr>';
            });
    }

    // ── Filtros ───────────────────────────────────────────────────────────────
    document.querySelectorAll('[data-log-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('[data-log-filter]').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            accionActiva = this.dataset.logFilter;
            cargar();
        });
    });

    searchEl?.addEventListener('input', function () {
        busquedaActual = this.value.trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(cargar, 350);
    });

    // ── Modal detalle ─────────────────────────────────────────────────────────
    const modalEl    = document.getElementById('modal-log-detalle');
    const modalBS    = modalEl ? new bootstrap.Modal(modalEl) : null;
    const detalleTbody = modalEl ? modalEl.querySelector('#tbl-log-detalle tbody') : null;

    const BADGE_LABELS = {
        LOGIN:   '<span class="badge-pill bp-green">LOGIN</span>',
        CREAR:   '<span class="badge-pill bp-blue">CREAR</span>',
        EDITAR:  '<span class="badge-pill bp-yellow">EDITAR</span>',
        ELIMINAR:'<span class="badge-pill bp-red">ELIMINAR</span>',
        SISTEMA: '<span class="badge-pill bp-gray">SISTEMA</span>'
    };

    function fila(label, valor) {
        return '<tr><th class="text-muted fw-normal ps-3 py-2" style="width:130px;font-size:.8rem;white-space:nowrap;">' +
            label + '</th><td class="py-2 pe-3" style="font-size:.85rem;">' + valor + '</td></tr>';
    }

    function verDetalle(id) {
        const l = logsCache[id];
        if (!l || !detalleTbody) return;

        const badgeHtml = BADGE_LABELS[l.accion] || '<span class="badge-pill bp-gray">' + esc(l.accion) + '</span>';

        detalleTbody.innerHTML =
            fila('Acción',      badgeHtml) +
            fila('Usuario',     '<strong>' + esc(l.usuario) + '</strong>') +
            fila('Tabla',       esc(l.tabla || '—')) +
            fila('ID Registro', l.registro_id ? '#' + esc(l.registro_id) : '—') +
            fila('Descripción', '<span style="white-space:pre-wrap;">' + esc(l.descripcion || '—') + '</span>') +
            fila('Dirección IP','<code>' + esc(l.ip || '—') + '</code>') +
            fila('Fecha y hora', formatFecha(l.fecha_hora));

        modalBS.show();
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-log-detalle');
        if (btn) verDetalle(btn.dataset.logId);
    });

    cargar();
})();
