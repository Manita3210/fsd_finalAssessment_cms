<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Dashboard</a>
    <a href="posts.php" class="<?php echo $current_page == 'posts.php' ? 'active' : ''; ?>">Posts</a>
    <a href="add_post.php" class="<?php echo $current_page == 'add_post.php' ? 'active' : ''; ?>">Add Post</a>
    <a href="categories.php" class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">Categories</a>
</div>