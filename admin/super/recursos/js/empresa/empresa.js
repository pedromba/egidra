/* EGIDRA — Empresa dinámico */
(function () {
    'use strict';

    const btnGuardar = document.getElementById('btn-guardar-empresa');
    const btnReset   = document.getElementById('btn-reset-empresa');

    // Inputs de archivo para logos
    const logoFileInput = document.getElementById('emp-logo-file');
    const logoBlancoFileInput = document.getElementById('emp-logo-blanco-file');
    const btnUploadLogo = document.getElementById('btn-upload-logo');
    const btnUploadLogoBlanc = document.getElementById('btn-upload-logo-blanco');
    const logoPreviewColor = document.getElementById('logo-preview-color');
    const logoPreviewWhite = document.getElementById('logo-preview-white');
    const logoFileName = document.getElementById('logo-file-name');
    const logoBlancoFileName = document.getElementById('logo-blanco-file-name');

    const fields = {
        nombre:      document.getElementById('emp-nombre'),
        slogan:      document.getElementById('emp-slogan'),
        descripcion: document.getElementById('emp-desc'),
        mision:      document.getElementById('emp-mision'),
        vision:      document.getElementById('emp-vision'),
        anio_fundacion: document.getElementById('emp-anio'),
        email:       document.getElementById('emp-email'),
        telefono:    document.getElementById('emp-tel'),
        direccion:   document.getElementById('emp-dir'),
        ciudad:      document.getElementById('emp-ciudad'),
        pais:        document.getElementById('emp-pais'),
        linkedin:    document.getElementById('emp-linkedin'),
        facebook:    document.getElementById('emp-facebook'),
        instagram:   document.getElementById('emp-instagram')
    };

    // Estado de archivos seleccionados
    let logoFile = null;
    let logoBlancoFile = null;

    // Manejador de selección de archivo para logo color
    logoFileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            logoFile = file;
            logoFileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                logoPreviewColor.innerHTML = '<img src="' + e.target.result + '" alt="Logo preview" style="max-width:100%; max-height:150px;">';
            };
            reader.readAsDataURL(file);
        }
    });

    // Manejador de selección de archivo para logo blanco
    logoBlancoFileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            logoBlancoFile = file;
            logoBlancoFileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                logoPreviewWhite.innerHTML = '<img src="' + e.target.result + '" alt="Logo preview" style="max-width:100%; max-height:150px;">';
            };
            reader.readAsDataURL(file);
        }
    });

    // Botones de upload
    btnUploadLogo?.addEventListener('click', function () { logoFileInput.click(); });
    btnUploadLogoBlanc?.addEventListener('click', function () { logoBlancoFileInput.click(); });

    function rellenar(data) {
        Object.keys(fields).forEach(function (key) {
            if (fields[key]) fields[key].value = data[key] || '';
        });

        // Mostrar logos existentes
        if (data.logo) {
            logoPreviewColor.innerHTML = '<img src="' + data.logo + '" alt="Logo" style="max-width:100%; max-height:150px;">';
            logoFileName.textContent = data.logo.split('/').pop();
        }
        if (data.logo_blanco) {
            logoPreviewWhite.innerHTML = '<img src="' + data.logo_blanco + '" alt="Logo blanco" style="max-width:100%; max-height:150px;">';
            logoBlancoFileName.textContent = data.logo_blanco.split('/').pop();
        }
    }

    function cargar() {
        fetch('../api/empresa/obtener.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.estado) rellenar(data.datos);
            })
            .catch(function () {
                Swal.fire({ title: 'Error', text: 'No se pudo cargar la información de empresa.', icon: 'error' });
            });
    }

    function guardar() {
        if (!fields.nombre.value.trim()) {
            Swal.fire({ title: 'Campo requerido', text: 'El nombre de la empresa es obligatorio.', icon: 'warning' });
            return;
        }
        const fd = new FormData();
        Object.keys(fields).forEach(function (key) {
            fd.append(key, fields[key] ? fields[key].value.trim() : '');
        });

        // Agregar archivos de logo si existen
        if (logoFile) fd.append('logo_file', logoFile);
        if (logoBlancoFile) fd.append('logo_blanco_file', logoBlancoFile);

        fetch('../api/empresa/guardar.php', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.estado) { Swal.fire({ title: 'Error', text: data.mensaje, icon: 'error' }); return; }
                // Limpiar archivos después de guardar correctamente
                logoFile = null;
                logoBlancoFile = null;
                logoFileInput.value = '';
                logoBlancoFileInput.value = '';
                // Recargar para mostrar los cambios
                cargar();
                Swal.fire({ title: 'Guardado', text: data.mensaje, icon: 'success', timer: 2000, showConfirmButton: false });
            })
            .catch(function () { Swal.fire({ title: 'Error de conexión', icon: 'error' }); });
    }

    btnGuardar?.addEventListener('click', guardar);

    btnReset?.addEventListener('click', function () {
        Swal.fire({
            title: '¿Restablecer?', text: 'Se perderán los cambios no guardados.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Sí, restablecer', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#6c757d', reverseButtons: true
        }).then(function (res) {
            if (res.isConfirmed) cargar();
        });
    });

    cargar();
})();
