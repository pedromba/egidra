/* EGIDRA — Proyectos Editor (CRUD + vista tabla / tarjetas) */
(function () {
    'use strict';

    const tbodyEl    = document.getElementById('tbody-proy');
    const gridEl     = document.getElementById('grid-proy');
    const vistTabla  = document.getElementById('vista-tabla');
    const statsEl    = document.getElementById('stats-proy');
    const filterCats = document.getElementById('filter-cats-proy');
    const btnTodos   = document.querySelector('[data-cat-filter=""]');
    const btnNuevo   = document.getElementById('btn-nuevo-proy');
    const btnGuardar = document.getElementById('btn-guardar-proy');
    const tituloMod  = document.getElementById('modal-proy-title');

    const fId       = document.getElementById('proy-id');
    const fTitulo   = document.getElementById('proy-titulo');
    const fCat      = document.getElementById('proy-cat');
    const fCli      = document.getElementById('proy-cli');
    const fUbic     = document.getElementById('proy-ubic');
    const fAno      = document.getElementById('proy-ano');
    const fDesc     = document.getElementById('proy-desc');
    const fDest     = document.getElementById('proy-dest');
    const fActivo   = document.getElementById('proy-activo');
    const fImgFile  = document.getElementById('proy-img-file');
    const fImgActual= document.getElementById('proy-img-actual');
    const fImgPrev  = document.getElementById('proy-img-preview');
    const fImgPh    = document.getElementById('proy-img-ph');

    const CAT_COLORS = ['bp-blue', 'bp-purple', 'bp-green', 'bp-yellow', 'bp-red'];
    let proyectos    = [];
    let catColores   = {};
    let vistaActual  = 'tabla';
    let filtroCat    = '';
    let filtroSearch = '';

    function esc(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str ?? ''));
        return d.innerHTML;
    }

    function catColor(catId) {
        return catColores[catId] || 'bp-gray';
    }

    /* ── Previsualización de imagen ── */
    fImgFile?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ title: 'Archivo muy grande', text: 'Máximo 2 MB.', icon: 'warning' });
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (e) {
            fImgPrev.src = e.target.result;
            fImgPrev.style.display = 'block';
            if (fImgPh) fImgPh.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    function setImgPreview(url) {
        if (url) {
            fImgPrev.src = url;
            fImgPrev.style.display = 'block';
            if (fImgPh) fImgPh.style.display = 'none';
        } else {
            fImgPrev.src = '';
            fImgPrev.style.display = 'none';
            if (fImgPh) fImgPh.style.display = '';
        }
        if (fImgFile) fImgFile.value = '';
    }

    /* ── Filtro en memoria ── */
    function filtrados() {
        return proyectos.filter(function (p) {
            const matchCat = !filtroCat || String(p.categoria_id) === String(filtroCat);
            const matchQ   = !filtroSearch ||
                (p.titulo + ' ' + (p.cliente_nombre || '') + ' ' + (p.ubicacion || ''))
                    .toLowerCase().includes(filtroSearch);
            return matchCat && matchQ;
        });
    }

    /* ── Render tabla ── */
    function renderTabla() {
        const lista = filtrados();
        if (!lista.length) {
            tbodyEl.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay proyectos que coincidan.</td></tr>';
            return;
        }
        tbodyEl.innerHTML = lista.map(function (p) {
            const imgThumb = p.imagen_url
                ? '<img src="' + esc(p.imagen_url) + '" style="width:36px;height:36px;object-fit:cover;border-radius:4px;margin-right:6px;vertical-align:middle;" alt="">'
                : '';
            return '<tr data-id="' + p.id + '">' +
                '<td style="font-weight:600;font-size:.82rem;">' + imgThumb + esc(p.titulo) + '</td>' +
                '<td><span class="badge-pill ' + catColor(p.categoria_id) + '">' + esc(p.categoria_nombre || '—') + '</span></td>' +
                '<td style="font-size:.8rem;">' + esc(p.cliente_nombre || '—') + '</td>' +
                '<td style="font-size:.78rem;color:var(--muted);"><i class="fas fa-map-marker-alt me-1"></i>' + esc(p.ubicacion || '—') + '</td>' +
                '<td style="font-size:.8rem;">' + esc(p.ano_finalizacion || '—') + '</td>' +
                '<td><label class="toggle-sw"><input type="checkbox" class="chk-dest" data-id="' + p.id + '"' + (p.es_destacado ? ' checked' : '') + '><span class="toggle-slider"></span></label></td>' +
                '<td><label class="toggle-sw"><input type="checkbox" class="chk-activo" data-id="' + p.id + '"' + (p.activo ? ' checked' : '') + '><span class="toggle-slider"></span></label></td>' +
                '<td><div class="d-flex gap-1">' +
                    '<button class="btn-icon edit edit-proy" data-id="' + p.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                    '<button class="btn-icon del del-proy"  data-id="' + p.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                '</div></td>' +
            '</tr>';
        }).join('');
    }

    /* ── Render cards ── */
    function renderCards() {
        const lista = filtrados();
        if (!lista.length) {
            gridEl.innerHTML = '<div class="col-12 text-center text-muted py-4">No hay proyectos que coincidan.</div>';
            return;
        }
        gridEl.innerHTML = lista.map(function (p) {
            const imgStyle = p.imagen_url
                ? 'background:url(' + esc(p.imagen_url) + ') center/cover no-repeat;'
                : 'background:var(--light-bg);';
            return '<div class="col-md-6 col-lg-4">' +
                '<div class="proy-card">' +
                    '<div class="proy-card-img" style="height:120px;border-radius:8px;margin-bottom:10px;' + imgStyle + '"></div>' +
                    '<div class="proy-card-head">' +
                        '<span class="badge-pill ' + catColor(p.categoria_id) + '">' + esc(p.categoria_nombre || 'Sin categoría') + '</span>' +
                        '<div class="d-flex gap-1">' +
                            '<button class="btn-icon edit edit-proy" data-id="' + p.id + '" title="Editar"><i class="fas fa-pen"></i></button>' +
                            '<button class="btn-icon del del-proy"  data-id="' + p.id + '" title="Eliminar"><i class="fas fa-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                    '<h6 class="proy-card-titulo">' + esc(p.titulo) + '</h6>' +
                    '<div class="proy-card-meta">' +
                        '<span><i class="fas fa-building"></i>' + esc(p.cliente_nombre || '—') + '</span>' +
                        '<span><i class="fas fa-map-marker-alt"></i>' + esc(p.ubicacion || '—') + '</span>' +
                        '<span><i class="fas fa-calendar-alt"></i>' + esc(p.ano_finalizacion || '—') + '</span>' +
                    '</div>' +
                    '<div class="proy-card-foot">' +
                        '<div class="d-flex align-items-center gap-2">' +
                            '<label class="toggle-sw"><input type="checkbox" class="chk-dest" data-id="' + p.id + '"' + (p.es_destacado ? ' checked' : '') + '><span class="toggle-slider"></span></label>' +
                            '<span style="font-size:.72rem;color:var(--muted);">Destacado</span>' +
                        '</div>' +
                        '<div class="d-flex align-items-center gap-2">' +
                            '<span style="font-size:.72rem;color:var(--muted);">Activo</span>' +
                            '<label class="toggle-sw"><input type="checkbox" class="chk-activo" data-id="' + p.id + '"' + (p.activo ? ' checked' : '') + '><span class="toggle-slider"></span></label>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function actualizar() {
        const lista = filtrados();
        if (statsEl) statsEl.textContent = lista.length + ' proyecto' + (lista.length !== 1 ? 's' : '');
        if (vistaActual === 'tabla') renderTabla();
        else renderCards();
    }

    /* ── Cargar desde API ── */
    function cargar() {
        const loading = '<tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>';
        tbodyEl.innerHTML = loading;

        fetch('../api/proyectos/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                proyectos = data.estado ? data.datos : [];
                actualizar();
            })
            .catch(function () {
                tbodyEl.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Error al cargar.</td></tr>';
            });
    }

    /* ── Poblar selects del modal ── */
    function poblarSelects() {
        fetch('../api/servicios/categorias/listar.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                fCat.innerHTML = '<option value="">— Categoría —</option>';
                if (!data.estado) return;
                data.datos.forEach(function (c, i) {
                    catColores[c.id] = CAT_COLORS[i % CAT_COLORS.length];
                    fCat.insertAdjacentHTML('beforeend', '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');

                    // Botones de filtro dinámicos
                    if (filterCats) {
                        const btn = document.createElement('button');
                        btn.className = 'btn-sec';
                        btn.dataset.catFilter = c.id;
                        btn.textContent = c.nombre;
                        filterCats.appendChild(btn);
                    }
                });
            });

        fetch('../api/proyectos/clientes.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                fCli.innerHTML = '<option value="">— Cliente —</option>';
                if (data.estado) data.datos.forEach(function (c) {
                    fCli.insertAdjacentHTML('beforeend', '<option value="' + c.id + '">' + esc(c.nombre) + '</option>');
                });
            });
    }

    /* ── Modal ── */
    function limpiarModal() {
        fId.value = ''; fTitulo.value = ''; fCat.value = ''; fCli.value = '';
        fUbic.value = ''; fAno.value = ''; fDesc.value = '';
        fDest.checked = false; fActivo.checked = true;
        fImgActual.value = '';
        setImgPreview('');
    }

    function abrirNuevo() {
        tituloMod.textContent = 'Nuevo Proyecto';
        limpiarModal();
        window.EgAdmin.openModal('modal-proy');
    }

    function findProy(id) {
        return proyectos.find(function (p) { return p.id == id; }) || null;
    }

    function abrirEditar(id) {
        const p = findProy(id);
        if (!p) return;
        tituloMod.textContent   = 'Editar Proyecto';
        fId.value               = p.id;
        fTitulo.value           = p.titulo              || '';
        fCat.value              = p.categoria_id        || '';
        fCli.value              = p.cliente_id          || '';
        fUbic.value             = p.ubicacion           || '';
        fAno.value              = p.ano_finalizacion    || '';
        fDesc.value             = p.descripcion_tecnica || '';
        fDest.checked           = !!p.es_destacado;
        fActivo.checked         = !!p.activo;
        fImgActual.value        = p.imagen              || '';
        setImgPreview(p.imagen_url || '');
        window.EgAdmin.openModal('modal-proy');
    }

    /* ── Guardar ── */
    function guardar() {
        if (!fTitulo.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El título es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        fd.append('id',               fId.value);
        fd.append('titulo',           fTitulo.value.trim());
        fd.append('categoria_id',     fCat.value);
        fd.append('cliente_id',       fCli.value);
        fd.append('ubicacion',        fUbic.value.trim());
        fd.append('ano_finalizacion', fAno.value || '');
        fd.append('descripcion',      fDesc.value.trim());
        fd.append('es_destacado',     fDest.checked   ? '1' : '0');
        fd.append('activo',           fActivo.checked ? '1' : '0');
        fd.append('imagen_actual',    fImgActual.value);

        if (fImgFile && fImgFile.files[0]) {
            fd.append('imagen_file', fImgFile.files[0]);
        }

        fetch('../api/proyectos/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                window.EgAdmin.closeModal('modal-proy');
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                cargar();
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    /* ── Eliminar ── */
    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar proyecto?', text: 'Esta acción no se puede deshacer.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c', cancelButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (!res.isConfirmed) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('../api/proyectos/eliminar.php', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                    Swal.fire({ title: 'Eliminado', text: data.mensaje, icon: 'success', timer: 1800, showConfirmButton: false });
                    cargar();
                })
                .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
        });
    }

    /* ── Toggle destacado / activo inline ── */
    function toggleCampo(id, campo, valor) {
        const p = findProy(id);
        if (!p) return;
        const fd = new FormData();
        fd.append('id',               p.id);
        fd.append('titulo',           p.titulo);
        fd.append('categoria_id',     p.categoria_id        || '');
        fd.append('cliente_id',       p.cliente_id          || '');
        fd.append('ubicacion',        p.ubicacion           || '');
        fd.append('ano_finalizacion', p.ano_finalizacion    || '');
        fd.append('descripcion',      p.descripcion_tecnica || '');
        fd.append('imagen_actual',    p.imagen              || '');
        fd.append('es_destacado',     campo === 'dest'   ? (valor ? '1' : '0') : (p.es_destacado ? '1' : '0'));
        fd.append('activo',           campo === 'activo' ? (valor ? '1' : '0') : (p.activo ? '1' : '0'));

        fetch('../api/proyectos/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) return;
                if (campo === 'dest')   p.es_destacado = valor;
                if (campo === 'activo') p.activo       = valor;
            });
    }

    /* ── Vista toggle ── */
    document.getElementById('btn-vista-tabla')?.addEventListener('click', function () {
        if (vistaActual === 'tabla') return;
        vistaActual = 'tabla';
        this.classList.add('active');
        document.getElementById('btn-vista-cards').classList.remove('active');
        vistTabla.style.removeProperty('display');
        gridEl.style.setProperty('display', 'none', 'important');
        actualizar();
    });

    document.getElementById('btn-vista-cards')?.addEventListener('click', function () {
        if (vistaActual === 'cards') return;
        vistaActual = 'cards';
        this.classList.add('active');
        document.getElementById('btn-vista-tabla').classList.remove('active');
        vistTabla.style.setProperty('display', 'none', 'important');
        gridEl.style.removeProperty('display');
        actualizar();
    });

    /* ── Filtros ── */
    btnTodos?.addEventListener('click', function () {
        document.querySelectorAll('[data-cat-filter]').forEach(function (b) { b.classList.remove('active'); });
        this.classList.add('active');
        filtroCat = '';
        actualizar();
    });

    filterCats?.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-cat-filter]');
        if (!btn) return;
        document.querySelectorAll('[data-cat-filter]').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        filtroCat = btn.dataset.catFilter;
        actualizar();
    });

    document.getElementById('search-proy')?.addEventListener('input', function () {
        filtroSearch = this.value.toLowerCase().trim();
        actualizar();
    });

    /* ── Event delegation ── */
    function delegarEventos(container) {
        container.addEventListener('click', function (e) {
            const editBtn = e.target.closest('.edit-proy');
            const delBtn  = e.target.closest('.del-proy');
            if (editBtn) abrirEditar(editBtn.dataset.id);
            if (delBtn)  eliminar(delBtn.dataset.id);
        });
        container.addEventListener('change', function (e) {
            const chkDest   = e.target.closest('.chk-dest');
            const chkActivo = e.target.closest('.chk-activo');
            if (chkDest)   toggleCampo(chkDest.dataset.id,   'dest',   chkDest.checked);
            if (chkActivo) toggleCampo(chkActivo.dataset.id, 'activo', chkActivo.checked);
        });
    }

    delegarEventos(tbodyEl);
    delegarEventos(gridEl);

    btnNuevo?.addEventListener('click', abrirNuevo);
    btnGuardar?.addEventListener('click', guardar);

    poblarSelects();
    cargar();
})();
