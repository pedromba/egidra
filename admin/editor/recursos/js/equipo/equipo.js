/* Equipo JS */
(function () {
    document.getElementById('search-equipo')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.team-card-wrap').forEach(el => {
            el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
