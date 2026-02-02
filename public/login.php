<?php
require_once '../config/db.php';
$page_title = "Login";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Security error. Please try again.";
    } else {
        $username = clean_input($_POST['username']);
        $password = $_POST['password'];
        
        // Prevent SQL injection with prepared statement
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <div style="color: red; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>