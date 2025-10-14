<?php
/**
 * SMM Mastery - Dashboard Footer Simple
 * Date: 12 Octobre 2025
 * Version: 1.0
 * Documentation: DOCS_DEV_TO_PROD\04_DEVELOPMENT_GUIDES\
 * Basé sur le footer professionnel de la landing page
 */
?>

</div> <!-- Fin main-content -->

<footer class="dashboard-footer">
    <div class="footer-container">
        
        <!-- Version compacte sur une ligne -->
        <div class="footer-compact">
            
            <!-- Gauche : Copyright & Features -->
            <div class="footer-left">
                <p class="footer-copyright">
                    <i class="fa-solid fa-shield-halved"></i>
                    &copy; <?php echo date('Y'); ?> <strong>SMM Mastery</strong>
                </p>
                <p class="footer-features">
                    <i class="fa-solid fa-lock"></i> Paiements sécurisés • 
                    <i class="fa-solid fa-rocket"></i> Livraison rapide • 
                    <i class="fa-solid fa-headset"></i> Support 24/7
                </p>
            </div>
            
            <!-- Centre : Navigation rapide -->
            <div class="footer-center">
                <a href="<?php echo SITE_URL; ?>/dashboard/index.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                <a href="<?php echo SITE_URL; ?>/services/index.php"><i class="fa-solid fa-layer-group"></i> Services</a>
                <a href="<?php echo SITE_URL; ?>/support/tickets.php"><i class="fa-solid fa-ticket"></i> Support</a>
                <a href="<?php echo SITE_URL; ?>/pages/terms.php"><i class="fa-solid fa-file-contract"></i> CGU</a>
            </div>
            
            <!-- Droite : Réseaux sociaux -->
            <div class="footer-right">
                <a href="#" class="social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="social-icon" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            
        </div>
        
    </div>
</footer>

<style>
/* ============================================
   DASHBOARD FOOTER - Style professionnel
   ============================================ */

/* Fix pour le flexbox du body */
.dashboard-page {
    flex-wrap: wrap;
}

.dashboard-footer {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
    color: white;
    padding: 20px;
    margin-top: 40px;
    margin-left: 260px;
    width: calc(100% - 260px);
    position: relative;
    clear: both;
    flex-basis: calc(100% - 260px);
    order: 999;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.footer-container {
    max-width: 100%;
    margin: 0 auto;
}

/* Footer compact sur une ligne */
.footer-compact {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    flex-wrap: wrap;
}

/* Section gauche - Copyright */
.footer-left {
    flex: 0 0 auto;
}

.footer-copyright {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0 0 5px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.footer-copyright strong {
    font-weight: 600;
}

.footer-features {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.6);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 4px;
}

.footer-features i {
    color: #60a5fa;
    font-size: 10px;
}

/* Section centre - Navigation */
.footer-center {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    flex: 1 1 auto;
    justify-content: center;
}

.footer-center a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.footer-center a:hover {
    color: white;
    transform: translateY(-2px);
}

.footer-center a i {
    font-size: 12px;
}

/* Section droite - Réseaux sociaux */
.footer-right {
    display: flex;
    gap: 12px;
    flex: 0 0 auto;
}

.social-icon {
    color: rgba(255, 255, 255, 0.6);
    font-size: 16px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-icon:hover {
    background: rgba(96, 165, 250, 0.3);
    color: white;
    transform: scale(1.15) translateY(-2px);
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-footer {
        margin-left: 0;
        width: 100%;
        flex-basis: 100%;
        padding: 20px 15px;
    }
    
    .footer-compact {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .footer-left,
    .footer-center,
    .footer-right {
        width: 100%;
        justify-content: center;
    }
    
    .footer-copyright,
    .footer-features {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .dashboard-footer {
        padding: 15px 10px;
        margin-top: 30px;
    }
    
    .footer-center {
        flex-direction: column;
        gap: 10px;
    }
    
    .footer-center a {
        font-size: 11px;
    }
    
    .social-icon {
        width: 28px;
        height: 28px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .footer-compact {
        gap: 12px;
    }
    
    .footer-copyright {
        font-size: 11px;
    }
    
    .footer-features {
        font-size: 10px;
    }
}
</style>

<!-- JavaScript -->
<script src="<?php echo SITE_URL; ?>/assets/js/user-dropdown-debug.js"></script>

<!-- Mobile Menu Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .footer-section h4 {
        font-size: 16px;
    }
    
    .footer-bottom p {
        flex-direction: column;
        gap: 5px;
    }
}

@media (max-width: 480px) {
    .dashboard-footer {
        padding: 30px 10px 15px;
    }
    
    .social-links {
        gap: 10px;
        font-size: 20px;
    }
    
    .social-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

<!-- JavaScript -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
<script src="<?php echo SITE_URL; ?>/assets/js/user-dropdown-debug.js"></script>

<!-- Mobile Menu Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (hamburgerBtn && sidebar && sidebarOverlay) {
        // Toggle sidebar
        hamburgerBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }
});
</script>

</body>
</html>
