<?php
require_once '../config/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Get posts
$posts = $pdo->query("SELECT p.*, u.username, c.name as category_name FROM posts p LEFT JOIN users u ON p.author_id = u.id LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'published' ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset")->fetchAll();

// Output HTML
foreach ($posts as $post):
?>
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