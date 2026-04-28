/* Proyectos JS */
(function () {
    // Filter by category
    document.querySelectorAll('[data-cat-filter]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('[data-cat-filter]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const cat = this.dataset.catFilter;
            document.querySelectorAll('tr[data-cat]').forEach(row => {
                row.style.display = (!cat || row.dataset.cat === cat) ? '' : 'none';
            });
        });
    });

    // Search
    document.getElementById('search-proy')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('tr[data-cat]').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();
