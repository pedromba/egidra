/* Certificaciones JS */
(function () {
    document.getElementById('search-cert')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.tbl tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
