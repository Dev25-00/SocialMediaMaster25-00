    </div> <!-- Fin main-content -->
    
    <!-- JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        // Toggle sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }
        
        // Hamburger button click
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', toggleSidebar);
        }
        
        // Overlay click
        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });
        
        // Close sidebar when clicking a link on mobile
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });
        });
    });
    </script>
    
    <?php if (isset($extra_scripts) && $extra_scripts): ?>
        <?php echo $extra_scripts; ?>
    <?php endif; ?>
    
</body>
</html>
