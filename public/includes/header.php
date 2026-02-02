<?php

require_once __DIR__ . '/../../config/config.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title ?? 'Blog CMS'; ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="header">
    <a href="index.php" class="logo">BlogTube</a>
    
    <form method="GET" action="search.php" class="search-box">
        <input type="text" name="q" class="search-input" placeholder="Search blog posts...">
        <button type="submit" class="search-btn">
            <span class="search-icon">🔍</span>
        </button>
    </form>
    <div>
        <?php if (check_login()): ?>
            <span class ="welcome-text"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            
            <?php if (is_admin() && basename(dirname($_SERVER['PHP_SELF'])) != 'admin'): ?>
                <a href="<?= BASE_URL ?>public/admin/index.php" class="btn">Admin Panel</a>
            <?php endif; ?>
            
            <a href="<?= BASE_URL ?>public/logout.php" class="btn btn-secondary">Logout</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>public/login.php" class="btn">Login</a>
            <a href="<?= BASE_URL ?>public/register.php" class="btn btn-secondary">Register</a>
        <?php endif; ?>
    </div>
</div>