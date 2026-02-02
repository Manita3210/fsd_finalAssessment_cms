<?php
require_once '../config/db.php';
$page_title = "Search Results";

$search = isset($_GET['q']) ? clean_input($_GET['q']) : '';

if (empty($search)) {
    header("Location: index.php");
    exit;
}

// Simple search
$stmt = $pdo->prepare("SELECT p.*, u.username, c.name as category_name FROM posts p LEFT JOIN users u ON p.author_id = u.id LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'published' AND (p.title LIKE ? OR p.content LIKE ?) ORDER BY p.created_at DESC");
$stmt->execute(["%$search%", "%$search%"]);
$posts = $stmt->fetchAll();

// Get categories for sidebar
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">Blog CMS</a>
        
        <form method="GET" action="search.php" class="search-box">
            <input type="text" name="q" class="search-input" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
        
        <div>
            <?php if (check_login()): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn btn-secondary">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar">
        <a href="index.php">Home</a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?category=<?php echo urlencode($cat['name']); ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="main-content">
        <h1>Search Results for "<?php echo htmlspecialchars($search); ?>"</h1>
        
        <?php if (empty($posts)): ?>
            <p>No posts found for your search.</p>
        <?php else: ?>
            <div id="posts-container">
                <?php foreach ($posts as $post): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?></p>
                    <div>
                        <span>By: <?php echo htmlspecialchars($post['username']); ?></span> | 
                        <span>Category: <?php echo htmlspecialchars($post['category_name']); ?></span> | 
                        <span>Date: <?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                    <a href="post.php?id=<?php echo $post['id']; ?>" class="btn">Read More</a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 20px;">
            <a href="index.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>