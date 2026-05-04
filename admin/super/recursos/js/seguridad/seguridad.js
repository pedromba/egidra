/* EGIDRA — Seguridad HSE dinámico */
(function () {
    'use strict';

    const listReglas  = document.getElementById('list-reglas');
    const gridStats   = document.getElementById('grid-stats');
    const btnNuevaStat= document.getElementById('btn-nueva-stat');
    const btnGuardaReg= document.getElementById('btn-guardar-regla');
    const btnGuardaStat=document.getElementById('btn-guardar-stat');
    const tituloStat  = document.getElementById('modal-stat-title');

    const fRegId    = document.getElementById('reg-id');
    const fRegTitulo= document.getElementById('reg-titulo');
    const fRegIcono = document.getElementById('reg-icono');
    const fRegDesc  = document.getElementById('reg-desc');

    const fStatId   = document.getElementById('stat-id');
    const fStatVal  = document.getElementById('stat-valor');
    const fStatEtiq = document.getElementById('stat-etiqueta');
    const fStatIcono= document.getElementById('stat-icono');
    const fStatOrden= document.getElementById('stat-orden');

    let reglas = [];
    let stats  = [];

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    /* ── Reglas ── */

    function renderReglas(lista) {
        if (!lista.length) {
            listReglas.innerHTML = '<div class="rule-row"><div class="text-center text-muted py-4 w-100">No hay reglas registradas.</div></div>';
            return;
        }
        listReglas.innerHTML = lista.map(function (r) {
            return '<div class="rule-row">' +
                '<div class="rule-num">' + esc(r.numero_orden) + '</div>' +
                '<div class="rule-icon-box"><i class="fas ' + esc(r.icono || 'fa-shield') + '"></i></div>' +
                '<div style="flex:1;min-width:0;">' +
                    '<div class="rule-title">' + esc(r.titulo) + '</div>' +
                    '<div class="rule-desc">' + esc(r.descripcion) + '</div>' +
                '</div>' +
                '<div class="rule-actions">' +
                    '<button class="btn-icon edit-rule" data-id="' + r.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargarReglas() {
        listReglas.innerHTML = '<div class="rule-row"><div class="text-center text-muted py-4 w-100"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div></div>';
        fetch('/egidra/admin/super/api/seguridad/reglas/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { reglas = data.estado ? data.datos : []; renderReglas(reglas); })
            .catch(function () { listReglas.innerHTML = '<div class="rule-row"><div class="text-center text-muted py-4 w-100">Error al cargar.</div></div>'; });
    }

    function abrirEditarRegla(id) {
        const r = reglas.find(function (x) { return x.id == id; });
        if (!r) return;
        fRegId.value     = r.id;
        fRegTitulo.value = r.titulo      || '';
        fRegIcono.value  = r.icono       || '';
        fRegDesc.value   = r.descripcion || '';
        window.EgAdmin.openModal('modal-edit-rule');
    }

    function guardarRegla() {
        if (!fRegTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',          fRegId.value);
        fd.append('titulo',      fRegTitulo.value.trim());
        fd.append('icono',       fRegIcono.value.trim());
        fd.append('descripcion', fRegDesc.value.trim());

        fetch('/egidra/admin/super/api/seguridad/reglas/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-edit-rule');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargarReglas();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    listReglas.addEventListener('click', function (e) {
        const btn = e.target.closest('.edit-rule');
        if (btn) abrirEditarRegla(btn.dataset.id);
    });

    btnGuardaReg?.addEventListener('click', guardarRegla);

    /* ── Estadísticas HSE ── */

    function renderStats(lista) {
        if (!lista.length) {
            gridStats.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay cifras HSE.</div>';
            return;
        }
        gridStats.innerHTML = lista.map(function (s, i) {
            return '<div class="col-6 col-md-3 stat-hse-cell' + (i > 0 ? ' stat-hse-border' : '') + '">' +
                '<div class="stat-hse-icon"><i class="fas ' + esc(s.icono || 'fa-chart-bar') + '"></i></div>' +
                '<div class="stat-hse-val">' + esc(s.valor) + '</div>' +
                '<div class="stat-hse-label">' + esc(s.etiqueta) + '</div>' +
                '<div class="stat-hse-actions">' +
                    '<button class="btn-icon edit-stat" data-id="' + s.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del-stat" data-id="' + s.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargarStats() {
        gridStats.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('/egidra/admin/super/api/seguridad/estadisticas/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) { stats = data.estado ? data.datos : []; renderStats(stats); })
            .catch(function () { gridStats.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar.</div>'; });
    }

    function abrirNuevaStat() {
        tituloStat.textContent = 'Nueva cifra HSE';
        fStatId.value = ''; fStatVal.value = ''; fStatEtiq.value = '';
        fStatIcono.value = ''; fStatOrden.value = '0';
        window.EgAdmin.openModal('modal-stat');
    }

    function abrirEditarStat(id) {
        const s = stats.find(function (x) { return x.id == id; });
        if (!s) return;
        tituloStat.textContent = 'Editar cifra HSE';
        fStatId.value    = s.id;
        fStatVal.value   = s.valor    || '';
        fStatEtiq.value  = s.etiqueta || '';
        fStatIcono.value = s.icono    || '';
        fStatOrden.value = s.orden ?? 0;
        window.EgAdmin.openModal('modal-stat');
    }

    function guardarStat() {
        if (!fStatVal.value.trim() || !fStatEtiq.value.trim()) {
            Swal.fire({ title: 'Campos requeridos', text: 'Valor y etiqueta son obligatorios.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',       fStatId.value);
        fd.append('valor',    fStatVal.value.trim());
        fd.append('etiqueta', fStatEtiq.value.trim());
        fd.append('icono',    fStatIcono.value.trim());
        fd.append('orden',    fStatOrden.value || '0');

        fetch('/egidra/admin/super/api/seguridad/estadisticas/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-stat');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargarStats();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminarStat(id) {
        Swal.fire({
            title: '¿Eliminar cifra?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('/egidra/admin/super/api/seguridad/estadisticas/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargarStats();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    gridStats.addEventListener('click', function (e) {
        const edit = e.target.closest('.edit-stat');
        const del  = e.target.closest('.del-stat');
        if (edit) abrirEditarStat(edit.dataset.id);
        if (del)  eliminarStat(del.dataset.id);
    });

    btnNuevaStat?.addEventListener('click', abrirNuevaStat);
    btnGuardaStat?.addEventListener('click', guardarStat);

    cargarReglas();
    cargarStats();
})();
