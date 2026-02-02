<?php
require_once '../config/db.php';
$page_title = "Blog CMS";

// Get search and category
$search = isset($_GET['q']) ? clean_input($_GET['q']) : '';
$category = isset($_GET['category']) ? clean_input($_GET['category']) : '';

// Simple pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$where = "status = 'published'";
$params = [];

if ($search) {
    $where .= " AND (title LIKE ? OR content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $where .= " AND category_id = (SELECT id FROM categories WHERE name = ?)";
    $params[] = $category;
}

// Get posts
$sql = "SELECT p.*, u.username, c.name as category_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $where 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Get categories for sidebar
$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $cat_stmt->fetchAll();

$has_more_posts = (count($posts) == $limit);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Recent Posts</h1>
        
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 50px; color: #718096;">
                <h3>No posts found</h3>
                <p>Be the first to create a post!</p>
            </div>
        <?php else: ?>
            <div id="posts-container">
                <?php foreach ($posts as $post): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?></p>
                    
                    <div class="card-meta">
                        <span>By: <?php echo htmlspecialchars($post['username']); ?></span> | 
                        <span>Category: <?php echo htmlspecialchars($post['category_name']); ?></span> | 
                        <span>Date: <?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="post.php?id=<?php echo $post['id']; ?>" class="btn">Read More</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!--AJAX Load More-->
            <?php if ($has_more_posts): ?>
            <div class="load-more-container">
                <button onclick="loadMore()" class="load-more-btn">Load More</button>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php include 'includes/footer.php'; ?>