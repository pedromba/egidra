/* Clientes JS */
(function () {
    document.getElementById('search-client')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.client-card-wrap').forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
