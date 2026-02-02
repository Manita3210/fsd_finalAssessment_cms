<?php
require_once '../config/db.php';

// Clear session
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit;
?>