<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title ?? 'Admin - Blog CMS'; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="header">
    <a href="../index.php" class="logo">BlogTube</a>
    <div>
        <a href="?switch_to_user=1" class="btn btn-secondary">User Mode</a>
        <a href="../logout.php" class="btn btn-secondary">Logout</a>
    </div>
</div>