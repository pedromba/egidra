/* Socios JS */
(function () {
    document.getElementById('search-socio')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.socio-row').forEach(el => {
            el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
