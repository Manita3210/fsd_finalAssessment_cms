<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get post to edit
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found!");
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    check_csrf();
    
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $status = clean_input($_POST['status']);
    
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, category_id = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $content, $category_id, $status, $id]);
    
    $success = "Post updated successfully!";
    // Refresh post data
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

$page_title = "Edit Post";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Edit Post</h1>
        
        <?php if (isset($success)): ?>
            <?php show_message($success); ?>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $post['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Update Post</button>
            <a href="posts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include 'includes/footer.php'; ?>