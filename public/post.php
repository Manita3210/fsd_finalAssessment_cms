<?php
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get post
$stmt = $pdo->prepare("SELECT p.*, u.username, c.name as category_name FROM posts p LEFT JOIN users u ON p.author_id = u.id LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ? AND p.status = 'published'");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found!");
}

//page title
$page_title = htmlspecialchars($post['title']) . " - Blog CMS";

// Get categories for sidebar
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $cat_stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
        
        <div class="post-meta">
            <span>By: <?php echo htmlspecialchars($post['username']); ?></span> | 
            <span>Category: <?php echo htmlspecialchars($post['category_name']); ?></span> | 
            <span>Date: <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
        </div>
        
        <div class="post-content card">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>
        
        <div class="post-actions">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>