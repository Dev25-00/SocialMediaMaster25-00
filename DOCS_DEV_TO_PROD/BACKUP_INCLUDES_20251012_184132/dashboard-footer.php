<?php
/**
 * FOOTER UNIFIÉ POUR DASHBOARD (Pages avec session)
 */
require_once __DIR__ . '/icons-config.php';
?>

    </main>
    
    <!-- Footer Dashboard -->
    <footer class="dashboard-footer">
        <div class="footer-container">
            <div class="footer-content">
                
                <!-- Links Quick -->
                <div class="footer-links">
                    <a href="<?php echo SITE_URL; ?>/pages/about.php">À propos</a>
                    <span class="footer-separator">•</span>
                    <a href="<?php echo SITE_URL; ?>/pages/terms.php">CGU</a>
                    <span class="footer-separator">•</span>
                    <a href="<?php echo SITE_URL; ?>/pages/privacy.php">Confidentialité</a>
                    <span class="footer-separator">•</span>
                    <a href="<?php echo SITE_URL; ?>/pages/faq.php">FAQ</a>
                    <span class="footer-separator">•</span>
                    <a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact</a>
                </div>
                
                <!-- Copyright -->
                <div class="footer-copyright">
                    <p>
                        <?php echo getIcon('shield', false, 'sm'); ?>
                        © <?php echo date('Y'); ?> <strong>SMM Mastery</strong> - Tous droits réservés
                    </p>
                    <p class="footer-tagline">
                        <?php echo getIcon('rocket', true, 'sm'); ?>
                        Votre partenaire pour une croissance sociale authentique
                    </p>
                </div>
                
                <!-- Social Links (optionnel) -->
                <div class="footer-social">
                    <a href="#" class="social-link" title="Facebook">
                        <?php echo getIcon('facebook'); ?>
                    </a>
                    <a href="#" class="social-link" title="Twitter">
                        <?php echo getIcon('twitter'); ?>
                    </a>
                    <a href="#" class="social-link" title="Instagram">
                        <?php echo getIcon('instagram'); ?>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <?php echo getIcon('linkedin'); ?>
                    </a>
                </div>
                
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/mobile-menu.js"></script>
    
    <!-- Script pour dropdowns -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des dropdowns (notifications, user menu)
        const dropdowns = document.querySelectorAll('.notifications-dropdown, .user-dropdown');
        
        dropdowns.forEach(dropdown => {
            const btn = dropdown.querySelector('button');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (btn && menu) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // Fermer autres dropdowns
                    dropdowns.forEach(d => {
                        if (d !== dropdown) {
                            d.querySelector('.dropdown-menu')?.classList.remove('show');
                        }
                    });
                    menu.classList.toggle('show');
                });
            }
        });
        
        // Fermer dropdowns au clic extérieur
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        });
        
        // Mobile menu
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.querySelector('.mobile-nav');
        const mobileClose = document.querySelector('.mobile-nav-close');
        const mobileOverlay = document.querySelector('.mobile-nav-overlay');
        
        if (mobileToggle && mobileNav) {
            mobileToggle.addEventListener('click', () => {
                mobileNav.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            [mobileClose, mobileOverlay].forEach(el => {
                if (el) {
                    el.addEventListener('click', () => {
                        mobileNav.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }
            });
        }
    });
    </script>
    
    <?php if (isset($extra_scripts) && $extra_scripts): ?>
        <?php echo $extra_scripts; ?>
    <?php endif; ?>
    
</body>
</html>
