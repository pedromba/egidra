/* EGIDRA — Seguridad HSE (Reglas + Estadísticas CRUD) */
(function () {
    'use strict';

    /* ── Shared ── */
    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    /* ════════════════════════════════════════
       REGLAS DE ORO
    ════════════════════════════════════════ */
    const reglasCont    = document.getElementById('reglas-container');
    const btnNuevaRegla = document.getElementById('btn-nueva-regla');
    const btnGuardarR   = document.getElementById('btn-guardar-regla');
    const tituloModR    = document.getElementById('modal-regla-title');

    const fRId     = document.getElementById('regla-id');
    const fRTitulo = document.getElementById('regla-titulo');
    const fRIcono  = document.getElementById('regla-icono');
    const fROrden  = document.getElementById('regla-orden');
    const fRDesc   = document.getElementById('regla-desc');
    const fRActivo = document.getElementById('regla-activo');

    let reglas = [];

    function renderReglas() {
        if (!reglas.length) {
            reglasCont.innerHTML = '<div class="rule-row"><span class="text-muted">No hay reglas registradas.</span></div>';
            return;
        }
        reglasCont.innerHTML = reglas.map(function (r, i) {
            const icono = r.icono ? r.icono : 'fa-shield-halved';
            return '<div class="rule-row" data-id="' + r.id + '">' +
                '<div class="rule-num">' + (r.numero_orden || (i + 1)) + '</div>' +
                '<div class="rule-icon-box"><i class="fas ' + esc(icono) + '"></i></div>' +
                '<div style="flex:1;min-width:0;">' +
                    '<div class="rule-title">' + esc(r.titulo) + (r.activo ? '' : ' <span class="badge-pill bp-gray ms-1">Inactiva</span>') + '</div>' +
                    '<div class="rule-desc">' + esc(r.descripcion || '') + '</div>' +
                '</div>' +
                '<div class="rule-actions">' +
                    '<button class="btn-icon edit edit-regla" data-id="' + r.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del del-regla"   data-id="' + r.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargarReglas() {
        reglasCont.innerHTML = '<div class="rule-row"><i class="fas fa-spinner fa-spin me-2 text-muted"></i><span class="text-muted">Cargando...</span></div>';
        fetch('../api/seguridad/reglas/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                reglas = data.estado ? data.datos : [];
                renderReglas();
            })
            .catch(function () {
                reglasCont.innerHTML = '<div class="rule-row text-muted">Error al cargar las reglas.</div>';
            });
    }

    function limpiarModalRegla() {
        fRId.value = ''; fRTitulo.value = ''; fRIcono.value = '';
        fROrden.value = ''; fRDesc.value = ''; fRActivo.checked = true;
    }

    function abrirNuevaRegla() {
        tituloModR.textContent = 'Nueva Regla de Oro';
        limpiarModalRegla();
        fROrden.value = reglas.length + 1;
        window.EgAdmin.openModal('modal-regla');
    }

    function abrirEditarRegla(id) {
        const r = reglas.find(function (x) { return x.id == id; });
        if (!r) return;
        tituloModR.textContent = 'Editar Regla de Oro';
        fRId.value     = r.id;
        fRTitulo.value = r.titulo        || '';
        fRIcono.value  = r.icono         || '';
        fROrden.value  = r.numero_orden  || '';
        fRDesc.value   = r.descripcion   || '';
        fRActivo.checked = !!r.activo;
        window.EgAdmin.openModal('modal-regla');
    }

    function guardarRegla() {
        if (!fRTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',     fRId.value);
        fd.append('titulo', fRTitulo.value.trim());
        fd.append('icono',  fRIcono.value.trim());
        fd.append('orden',  fROrden.value || 0);
        fd.append('desc',   fRDesc.value.trim());
        fd.append('activo', fRActivo.checked ? '1' : '0');

        fetch('../api/seguridad/reglas/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-regla');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargarReglas();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    function eliminarRegla(id) {
        const r = reglas.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar regla?',
            text: (r ? r.titulo : 'Esta regla') + ' será eliminada permanentemente.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/seguridad/reglas/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminada', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargarReglas();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    reglasCont.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-regla');
        const delBtn  = e.target.closest('.del-regla');
        if (editBtn) abrirEditarRegla(editBtn.dataset.id);
        if (delBtn)  eliminarRegla(delBtn.dataset.id);
    });

    btnNuevaRegla?.addEventListener('click', abrirNuevaRegla);
    btnGuardarR?.addEventListener('click', guardarRegla);

    /* ════════════════════════════════════════
       ESTADÍSTICAS HSE
    ════════════════════════════════════════ */
    const statsCont     = document.getElementById('stats-container');
    const btnNuevaStat  = document.getElementById('btn-nueva-stat');
    const btnGuardarS   = document.getElementById('btn-guardar-stat');
    const tituloModS    = document.getElementById('modal-stat-title');

    const fSId       = document.getElementById('stat-id');
    const fSValor    = document.getElementById('stat-valor');
    const fSEtiqueta = document.getElementById('stat-etiqueta');
    const fSIcono    = document.getElementById('stat-icono');
    const fSOrden    = document.getElementById('stat-orden');

    let estadisticas = [];

    function renderStats() {
        if (!estadisticas.length) {
            statsCont.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay cifras registradas.</div>';
            return;
        }
        statsCont.innerHTML = estadisticas.map(function (s, i) {
            const icono  = s.icono ? s.icono : 'fa-chart-simple';
            const border = i > 0 ? ' stat-hse-border' : '';
            return '<div class="col-6 col-md-3 stat-hse-cell' + border + '" data-id="' + s.id + '">' +
                '<div class="stat-hse-icon"><i class="fas ' + esc(icono) + '"></i></div>' +
                '<div class="stat-hse-val">' + esc(s.valor) + '</div>' +
                '<div class="stat-hse-label">' + esc(s.etiqueta) + '</div>' +
                '<div class="stat-hse-actions">' +
                    '<button class="btn-icon edit edit-stat" data-id="' + s.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del del-stat"   data-id="' + s.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function cargarStats() {
        statsCont.innerHTML = '<div class="col-12 text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</div>';
        fetch('../api/seguridad/estadisticas/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                estadisticas = data.estado ? data.datos : [];
                renderStats();
            })
            .catch(function () {
                statsCont.innerHTML = '<div class="col-12 text-center text-muted py-4">Error al cargar las cifras.</div>';
            });
    }

    function limpiarModalStat() {
        fSId.value = ''; fSValor.value = ''; fSEtiqueta.value = '';
        fSIcono.value = ''; fSOrden.value = '';
    }

    function abrirNuevaStat() {
        tituloModS.textContent = 'Nueva Cifra HSE';
        limpiarModalStat();
        fSOrden.value = estadisticas.length;
        window.EgAdmin.openModal('modal-stat');
    }

    function abrirEditarStat(id) {
        const s = estadisticas.find(function (x) { return x.id == id; });
        if (!s) return;
        tituloModS.textContent = 'Editar Cifra HSE';
        fSId.value       = s.id;
        fSValor.value    = s.valor    || '';
        fSEtiqueta.value = s.etiqueta || '';
        fSIcono.value    = s.icono    || '';
        fSOrden.value    = s.orden    || 0;
        window.EgAdmin.openModal('modal-stat');
    }

    function guardarStat() {
        if (!fSValor.value.trim() || !fSEtiqueta.value.trim()) {
            Swal.fire({ title: 'Campos requeridos', text: 'El valor y la etiqueta son obligatorios.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',       fSId.value);
        fd.append('valor',    fSValor.value.trim());
        fd.append('etiqueta', fSEtiqueta.value.trim());
        fd.append('icono',    fSIcono.value.trim());
        fd.append('orden',    fSOrden.value || 0);

        fetch('../api/seguridad/estadisticas/guardar.php', { method: 'POST', body: fd })
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
        const s = estadisticas.find(function (x) { return x.id == id; });
        Swal.fire({
            title: '¿Eliminar cifra?',
            text: (s ? '"' + s.valor + ' — ' + s.etiqueta + '"' : 'Esta cifra') + ' será eliminada.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/seguridad/estadisticas/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminada', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargarStats();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    statsCont.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit-stat');
        const delBtn  = e.target.closest('.del-stat');
        if (editBtn) abrirEditarStat(editBtn.dataset.id);
        if (delBtn)  eliminarStat(delBtn.dataset.id);
    });

    btnNuevaStat?.addEventListener('click', abrirNuevaStat);
    btnGuardarS?.addEventListener('click', guardarStat);

    /* ── Init ── */
    cargarReglas();
    cargarStats();
})();
