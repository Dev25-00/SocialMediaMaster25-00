/**
 * SMM Mastery - Mobile Menu
 * Gestion du menu mobile responsive
 */

document.addEventListener('DOMContentLoaded', function () {

    // Créer le bouton menu mobile s'il n'existe pas
    if (!document.querySelector('.mobile-menu-btn')) {
        const menuBtn = document.createElement('button');
        menuBtn.className = 'mobile-menu-btn';
        menuBtn.innerHTML = '☰';
        menuBtn.setAttribute('aria-label', 'Toggle menu');
        document.body.appendChild(menuBtn);

        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        const sidebar = document.querySelector('.sidebar');

        // Toggle menu
        menuBtn.addEventListener('click', function () {
            if (sidebar) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                menuBtn.innerHTML = sidebar.classList.contains('active') ? '✕' : '☰';
            }
        });

        // Fermer avec overlay
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            menuBtn.innerHTML = '☰';
        });

        // Fermer avec ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                menuBtn.innerHTML = '☰';
            }
        });
    }

    // Auto-fermer le menu mobile après un clic sur un lien
    const sidebarLinks = document.querySelectorAll('.sidebar .nav-item');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                const menuBtn = document.querySelector('.mobile-menu-btn');

                if (sidebar) sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
                if (menuBtn) menuBtn.innerHTML = '☰';
            }
        });
    });

});
