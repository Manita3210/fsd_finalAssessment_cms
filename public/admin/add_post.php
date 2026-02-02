<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    check_csrf();
    
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $status = clean_input($_POST['status']);
    $author_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, author_id, category_id, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $author_id, $category_id, $status]);
    
    $success = "Post added successfully!";
}

$page_title = "Add Post";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Add New Post</h1>
        
        <?php if (isset($success)): ?>
            <?php show_message($success); ?>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="10" required></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Save Post</button>
            <a href="posts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include 'includes/footer.php'; ?>