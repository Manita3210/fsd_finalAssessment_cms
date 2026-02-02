<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
}

// pagination to Get all posts
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit = 15; 
$offset = ($page - 1) * $limit;
$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$total_pages = ceil($total_posts / $limit);

// Get posts WITH LIMIT
// Get posts WITH LIMIT - FIXED PDO BINDING
$stmt = $pdo->prepare("SELECT p.*, u.username, c.name as category_name 
                       FROM posts p 
                       LEFT JOIN users u ON p.author_id = u.id 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       ORDER BY created_at DESC 
                       LIMIT :limit OFFSET :offset");
                       
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$page_title = "Manage Posts";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Manage Posts</h1>
        <a href="add_post.php" class="btn">Add New Post</a>
        
        <table class="table">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo htmlspecialchars($post['username']); ?></td>
                <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($post['created_at'])); ?></td>
                <td><?php echo $post['status']; ?></td>
                <td>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                    <a href="posts.php?delete=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?')" class="btn btn-secondary">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
                </table>
        
        <!-- ADD PAGINATION CONTROLS HERE -->
        <?php if ($total_pages > 1): ?>
        <div style="margin-top: 30px; text-align: center;">
            <div style="display: inline-flex; gap: 10px; align-items: center;">
                <?php if ($page > 1): ?>
                    <a href="posts.php?page=<?php echo $page - 1; ?>" class="btn">« Previous</a>
                <?php endif; ?>
                
                <span style="padding: 8px 15px; background: #f8f9fa; border-radius: 4px;">
                    Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                </span>
                
                <?php if ($page < $total_pages): ?>
                    <a href="posts.php?page=<?php echo $page + 1; ?>" class="btn">Next »</a>
                <?php endif; ?>
            </div>
            
            <!-- Page number links -->
            <div style="margin-top: 15px;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span style="padding: 5px 10px; background: #dc3545; color: white; border-radius: 3px; margin: 0 2px;">
                            <?php echo $i; ?>
                        </span>
                    <?php else: ?>
                        <a href="posts.php?page=<?php echo $i; ?>" style="padding: 5px 10px; background: #f8f9fa; border-radius: 3px; margin: 0 2px; text-decoration: none; color: #333;">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    </div>

<?php include 'includes/footer.php'; ?>