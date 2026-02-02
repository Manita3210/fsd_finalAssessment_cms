<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

// Get stats
$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$published_posts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Recent posts
$recent_posts = $pdo->query("SELECT p.*, u.username FROM posts p LEFT JOIN users u ON p.author_id = u.id ORDER BY created_at DESC LIMIT 5")->fetchAll();

$page_title = "Admin Dashboard";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Admin Dashboard</h1>
        
        <!-- Stats -->
        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div class="card">
                <h3>Total Posts</h3>
                <div style="font-size: 24px;"><?php echo $total_posts; ?></div>
            </div>
            <div class="card">
                <h3>Published Posts</h3>
                <div style="font-size: 24px;"><?php echo $published_posts; ?></div>
            </div>
            <div class="card">
                <h3>Total Users</h3>
                <div style="font-size: 24px;"><?php echo $total_users; ?></div>
            </div>
        </div>

        <!-- Recent Posts -->
        <h2>Recent Posts</h2>
        <table class="table">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($recent_posts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['title']); ?></td>
                <td><?php echo htmlspecialchars($post['username']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($post['created_at'])); ?></td>
                <td><?php echo $post['status']; ?></td>
                <td>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
                    <a href="posts.php?delete=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?')" class="btn btn-secondary">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

<?php include 'includes/footer.php'; ?>