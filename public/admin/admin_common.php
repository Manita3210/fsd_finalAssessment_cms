<?php
require_once '../../config/db.php';

// Admin authorization
if (!check_login() || !is_admin()) {
    header("Location: ../login.php");
    exit;
}

// CSRF check function
function check_csrf() {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Security error: Invalid CSRF token.");
    }
}
?>