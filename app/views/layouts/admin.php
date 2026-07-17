<?php
// Layout: admin.php
// Shared layout for all admin dashboard pages
// TODO: Implement in Tugas 3
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin | <?= $title ?? 'SobatBogor' ?></title>
</head>
<body>
    <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
    <main><?= $content ?? '' ?></main>
</body>
</html>
