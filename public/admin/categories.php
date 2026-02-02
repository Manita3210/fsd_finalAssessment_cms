<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

// Handle actions
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Check for posts in category
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE category_id = ?");
    $stmt->execute([$id]);
    $post_count = $stmt->fetchColumn();
    
    if ($post_count > 0) {
        $error = "Cannot delete category that contains $post_count post(s). 
                 Please delete or move the posts first.";
    } else {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        $message = "Category deleted successfully!";
    }
}

// Add new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    check_csrf();
    
    $name = clean_input($_POST['name']);
    $description = clean_input($_POST['description']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $message = "Category added!";
    }
}

// Get all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$page_title = "Manage Categories";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Manage Categories</h1>
        
        <?php if (isset($error)): ?>
            <?php show_message($error, 'error'); ?>
        <?php endif; ?>
        
        <?php if (isset($message)): ?>
            <?php show_message($message); ?>
        <?php endif; ?>
        
        <!-- Add Category Form -->
        <div class="card" style="margin-bottom: 20px;">
            <h3>Add New Category</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                
                <button type="submit" name="add_category" class="btn">Add Category</button>
            </form>
        </div>
        
        <!-- Categories List -->
        <h2>All Categories</h2>
        <?php if (empty($categories)): ?>
            <p>No categories yet.</p>
        <?php else: ?>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn">Edit</a>
                        <a href="categories.php?delete=<?php echo $category['id']; ?>" 
                           onclick="return confirm('Delete this category?')" 
                           class="btn btn-secondary">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

<?php include 'includes/footer.php'; ?>