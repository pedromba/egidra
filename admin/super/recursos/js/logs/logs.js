/* EGIDRA — Logs de Actividad dinámico */
(function () {
    'use strict';

    const tbody   = document.getElementById('tbody-logs');
    const searchEl= document.getElementById('search-log');

    const BADGES = {
        LOGIN:   'bp-green',
        CREAR:   'bp-blue',
        EDITAR:  'bp-yellow',
        ELIMINAR:'bp-red',
        SISTEMA: 'bp-gray'
    };

    let accionActiva = '';
    let busquedaActual = '';
    let searchTimer = null;

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function formatFecha(f) {
        if (!f) return '—';
        const d = new Date(f);
        return d.toLocaleString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function renderTable(lista) {
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay registros.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map(function (l) {
            const badge = BADGES[l.accion] || 'bp-gray';
            return '<tr>' +
                '<td><span class="badge-pill ' + badge + '">' + esc(l.accion) + '</span></td>' +
                '<td style="font-size:.8rem;font-weight:600;">' + esc(l.usuario) + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);">' + esc(l.tabla || '—') + '</td>' +
                '<td style="font-size:.8rem;">' + esc(l.descripcion || '—') + '</td>' +
                '<td style="font-size:.72rem;color:var(--muted);font-family:monospace;">' + esc(l.ip || '—') + '</td>' +
                '<td style="font-size:.75rem;color:var(--muted);white-space:nowrap;">' + formatFecha(l.fecha_hora) + '</td>' +
            '</tr>';
        }).join('');
    }

    function cargar() {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        let url = '../api/logs/listar.php';
        const params = [];
        if (accionActiva) params.push('accion=' + encodeURIComponent(accionActiva));
        if (busquedaActual) params.push('q=' + encodeURIComponent(busquedaActual));
        if (params.length) url += '?' + params.join('&');

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) { renderTable(data.estado ? data.datos : []); })
            .catch(function () { tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Error al cargar.</td></tr>'; });
    }

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

    cargar();
})();
