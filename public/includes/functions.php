<?php
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function check_login() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function show_message($message, $type = 'success') {
    $bg = $type == 'success' ? '#d4edda' : '#f8d7da';
    $color = $type == 'success' ? '#155724' : '#721c24';
    $border = $type == 'success' ? '#c3e6cb' : '#f5c6cb';
    echo "<div style='background: $bg; color: $color; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid $border;'>$message</div>";
}
?>