<?php
session_start();

// $host = 'localhost';
// $dbname = 'blog_db';
// $username = 'root';
// $password = '';

$host = 'localhost';
$dbname = 'np03cs4a240294';
$username = 'np03cs4a240294';
$password = 'w6BnRwtJHX';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Load functions
require_once __DIR__ . '/../public/includes/functions.php';

// Admin to user mode
if (isset($_GET['switch_to_user']) && is_admin()) {
    $_SESSION['view_mode'] = 'user';
    header("Location: ../index.php");
    exit;
}
?>