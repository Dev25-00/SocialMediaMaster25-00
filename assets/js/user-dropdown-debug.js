/**
 * SMM Mastery - User Dropdown Debug & Fix
 * Date: 12 Octobre 2025
 * Version: 1.0
 * Documentation: DOCS_DEV_TO_PROD\05_FIXES_PATCHES\
 */

(function () {
    'use strict';

    console.log('🔍 User Dropdown Debug Script Loaded');

    // Fonction d'initialisation
    function initUserDropdown() {
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        console.log('📍 Elements found:', {
            userMenuBtn: !!userMenuBtn,
            userDropdown: !!userDropdown
        });

        if (!userMenuBtn || !userDropdown) {
            console.error('❌ Elements not found!');
            return;
        }

        // Toggle dropdown
        userMenuBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isActive = userDropdown.classList.contains('active');
            console.log('🖱️ Click on user menu button. Active:', isActive);

            userDropdown.classList.toggle('active');

            // Debug: Vérifier les styles appliqués
            if (userDropdown.classList.contains('active')) {
                const styles = window.getComputedStyle(userDropdown);
                console.log('✅ Dropdown activated. Computed styles:', {
                    visibility: styles.visibility,
                    opacity: styles.opacity,
                    transform: styles.transform,
                    zIndex: styles.zIndex,
                    display: styles.display,
                    position: styles.position
                });
            } else {
                console.log('⏹️ Dropdown deactivated');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                if (userDropdown.classList.contains('active')) {
                    console.log('🔄 Closing dropdown (click outside)');
                    userDropdown.classList.remove('active');
                }
            }
        });

        // Prevent dropdown from closing when clicking inside it
        userDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        console.log('✅ User dropdown initialized successfully');
    }

    // Attendre que le DOM soit chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUserDropdown);
    } else {
        initUserDropdown();
    }
})();
