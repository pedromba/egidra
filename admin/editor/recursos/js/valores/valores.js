/* Valores JS */
(function () {
    document.getElementById('search-valor')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.valor-card-wrap').forEach(el => {
            el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    document.getElementById('icono-input')?.addEventListener('input', function () {
        const preview = document.getElementById('icono-preview-i');
        if (preview) {
            preview.className = 'fas ' + this.value.trim();
        }
    });
})();
