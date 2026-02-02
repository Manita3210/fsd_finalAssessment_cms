<?php
require_once '../../config/db.php';
require_once 'admin_common.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category to edit
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    die("Category not found!");
}

// Update category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    check_csrf();
    
    $name = clean_input($_POST['name']);
    $description = clean_input($_POST['description']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);
        $message = "Category updated!";
        
        // Refresh category data
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();
    }
}

$page_title = "Edit Category";
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Edit Category</h1>
        
        <?php if (isset($message)): ?>
            <?php show_message($message); ?>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
            </div>
            
            <button type="submit" class="btn">Update Category</button>
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php include 'includes/footer.php'; ?>