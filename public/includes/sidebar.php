<?php
$category = $category ?? '';
?>
<div class="sidebar">
    <a href="index.php" class="<?php echo !isset($category) || !$category ? 'active' : ''; ?>">Home</a>
    <?php if (isset($categories)): ?>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?category=<?php echo urlencode($cat['name']); ?>" 
               class="<?php echo (isset($category) && $category == $cat['name']) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>