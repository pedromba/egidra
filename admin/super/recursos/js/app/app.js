/* EGIDRA Admin — Shared JS (sidebar toggle + modal) */

(function () {
    'use strict';

    // ── Sidebar toggle ────────────────────────────────────────
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sb-overlay');
    const toggle   = document.getElementById('tb-toggle');

    function openSb()  { sidebar?.classList.add('open');    overlay?.classList.add('show'); }
    function closeSb() { sidebar?.classList.remove('open'); overlay?.classList.remove('show'); }

    toggle?.addEventListener('click', openSb);
    overlay?.addEventListener('click', closeSb);

    // close on ESC
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeSb(); closeAllModals(); closeUserMenu(); } });

    // ── User dropdown ─────────────────────────────────────────
    const userBtn      = document.getElementById('tb-user-btn');
    const userDropdown = document.getElementById('tb-dropdown');

    function openUserMenu()  {
        userDropdown?.classList.add('show');
        userBtn?.classList.add('open');
        userBtn?.setAttribute('aria-expanded', 'true');
    }
    function closeUserMenu() {
        userDropdown?.classList.remove('show');
        userBtn?.classList.remove('open');
        userBtn?.setAttribute('aria-expanded', 'false');
    }
    function toggleUserMenu() {
        userDropdown?.classList.contains('show') ? closeUserMenu() : openUserMenu();
    }

    userBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleUserMenu();
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#tb-user-wrap')) closeUserMenu();
    });

    // ── Modal helpers ─────────────────────────────────────────
    function openModal(id)  {
        const m = document.getElementById(id);
        m?.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        m?.classList.remove('show');
        document.body.style.overflow = '';
    }
    function closeAllModals() {
        document.querySelectorAll('.modal-backdrop-custom.show')
            .forEach(m => { m.classList.remove('show'); });
        document.body.style.overflow = '';
    }

    // Delegate open/close via data attrs
    document.addEventListener('click', function (e) {
        const opener = e.target.closest('[data-modal-open]');
        const closer = e.target.closest('[data-modal-close]');
        const backdrop = e.target.closest('.modal-backdrop-custom');

        if (opener) openModal(opener.dataset.modalOpen);
        if (closer) closeModal(closer.dataset.modalClose);
        if (backdrop && e.target === backdrop) closeAllModals();
    });

    // ── Logout con confirmación SweetAlert2 ───────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-logout');
        if (!btn) return;
        e.preventDefault();
        const url = btn.dataset.logoutUrl;
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: 'Se cerrará tu sesión actual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) window.location.href = url;
        });
    });

    // expose
    window.EgAdmin = { openModal, closeModal };
})();

// ── Header badges dinámicos ───────────────────────────────────────────────────
(function () {
    function setBadge(id, n) {
        const el = document.getElementById(id);
        if (!el) return;
        if (n > 0) { el.textContent = n > 99 ? '99+' : n; el.style.display = 'flex'; }
        else        { el.style.display = 'none'; }
    }
    function actualizar() {
        fetch('../api/mensajes/no-leidos.php').then(r => r.json()).then(d => setBadge('badge-mensajes', d.total || 0)).catch(() => {});
        fetch('../api/logs/recientes.php').then(r => r.json()).then(d => setBadge('badge-logs', d.total || 0)).catch(() => {});
    }
    actualizar();
    setInterval(actualizar, 60000);
})();
