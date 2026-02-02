<?php
require_once __DIR__ . '/../../config/config.php';
?>

<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-copyright">
            <p>&copy; <?php echo date('Y'); ?> Blog CMS. All rights reserved.</p>
            <p class="footer-links">
                <a href="<?php echo BASE_URL; ?>public/index.php">Home</a> | 
                <a href="<?php echo BASE_URL; ?>public/search.php">Search</a> | 
                <?php if (check_login()): ?>
                    <a href="<?php echo BASE_URL; ?>public/logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>public/login.php">Login</a> | 
                    <a href="<?php echo BASE_URL; ?>public/register.php">Register</a>
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer>

<?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
<?php endif; ?>
</body>
</html>