/* EGIDRA — Logs de Actividad */
(function () {
    'use strict';

    const tbody     = document.getElementById('tbody-logs');
    const searchEl  = document.getElementById('search-log');
    const pagDiv    = document.getElementById('logs-pagination');
    const pagInfo   = document.getElementById('logs-pag-info');
    const pagBtns   = document.getElementById('logs-pag-btns');

    const BADGES = {
        LOGIN:   'bp-green', CREAR: 'bp-blue', EDITAR: 'bp-yellow',
        ELIMINAR:'bp-red',   SISTEMA:'bp-gray'
    };

    let accionActiva  = '';
    let busquedaActual = '';
    let searchTimer   = null;
    let logsCache     = {};
    let paginaActual  = 1;
    const POR_PAG     = 10;

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function formatFecha(f) {
        if (!f) return '—';
        return new Date(f).toLocaleString('es-ES', {
            day:'2-digit', month:'2-digit', year:'numeric',
            hour:'2-digit', minute:'2-digit', second:'2-digit'
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

    function renderPaginacion(total, pagina, paginas) {
        if (paginas <= 1) { pagDiv.style.display = 'none'; return; }
        pagDiv.style.display = '';

        const desde = (pagina - 1) * POR_PAG + 1;
        const hasta = Math.min(pagina * POR_PAG, total);
        pagInfo.textContent = 'Mostrando ' + desde + '–' + hasta + ' de ' + total + ' registros';

        // Calcula rango visible: siempre 5 botones centrados en la página actual
        const rango = 2;
        let inicio = Math.max(1, pagina - rango);
        let fin    = Math.min(paginas, pagina + rango);
        if (fin - inicio < rango * 2) {
            if (inicio === 1) fin = Math.min(paginas, inicio + rango * 2);
            else inicio = Math.max(1, fin - rango * 2);
        }

        let html = '';
        const btnStyle = 'min-width:32px;height:32px;border-radius:8px;border:1px solid var(--border);background:var(--body-bg);color:var(--text);font-size:.78rem;cursor:pointer;';
        const btnActivo = 'min-width:32px;height:32px;border-radius:8px;border:1px solid var(--primary);background:var(--primary);color:#000;font-size:.78rem;font-weight:700;cursor:pointer;';

        html += '<button style="' + btnStyle + '" data-pag="' + (pagina - 1) + '" ' + (pagina <= 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>';
        if (inicio > 1) html += '<button style="' + btnStyle + '" data-pag="1">1</button>' + (inicio > 2 ? '<span style="padding:0 4px;color:var(--muted);">…</span>' : '');

        for (let p = inicio; p <= fin; p++) {
            html += '<button style="' + (p === pagina ? btnActivo : btnStyle) + '" data-pag="' + p + '">' + p + '</button>';
        }

        if (fin < paginas) html += (fin < paginas - 1 ? '<span style="padding:0 4px;color:var(--muted);">…</span>' : '') + '<button style="' + btnStyle + '" data-pag="' + paginas + '">' + paginas + '</button>';
        html += '<button style="' + btnStyle + '" data-pag="' + (pagina + 1) + '" ' + (pagina >= paginas ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>';

        pagBtns.innerHTML = html;
    }

    function cargar(pagina) {
        paginaActual = pagina || 1;
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        pagDiv.style.display = 'none';

        const params = ['pagina=' + paginaActual, 'por_pagina=' + POR_PAG];
        if (accionActiva)   params.push('accion=' + encodeURIComponent(accionActiva));
        if (busquedaActual) params.push('q='      + encodeURIComponent(busquedaActual));

        fetch('../api/logs/listar.php?' + params.join('&'))
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Error al cargar.</td></tr>'; return; }
                renderTable(data.datos);
                renderPaginacion(data.total, data.pagina, data.paginas);
            })
            .catch(function () {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Error al cargar.</td></tr>';
            });
    }

    // ── Filtros ────────────────────────────────────────────────────────────────
    document.querySelectorAll('[data-log-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('[data-log-filter]').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            accionActiva = this.dataset.logFilter;
            cargar(1);
        });
    });

    searchEl?.addEventListener('input', function () {
        busquedaActual = this.value.trim();
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () { cargar(1); }, 350);
    });

    // ── Paginación (delegación) ────────────────────────────────────────────────
    pagBtns?.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-pag]');
        if (!btn || btn.disabled) return;
        cargar(parseInt(btn.dataset.pag));
    });

    // ── Modal detalle ──────────────────────────────────────────────────────────
    const modalEl      = document.getElementById('modal-log-detalle');
    const modalBS      = modalEl ? new bootstrap.Modal(modalEl) : null;
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
            fila('Acción',       badgeHtml) +
            fila('Usuario',      '<strong>' + esc(l.usuario) + '</strong>') +
            fila('Tabla',        esc(l.tabla || '—')) +
            fila('ID Registro',  l.registro_id ? '#' + esc(l.registro_id) : '—') +
            fila('Descripción',  '<span style="white-space:pre-wrap;">' + esc(l.descripcion || '—') + '</span>') +
            fila('Dirección IP', '<code>' + esc(l.ip || '—') + '</code>') +
            fila('Fecha y hora', formatFecha(l.fecha_hora));
        modalBS.show();
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-log-detalle');
        if (btn) verDetalle(btn.dataset.logId);
    });

    cargar(1);
})();
