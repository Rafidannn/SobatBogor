<?php
// Layout: main.php
// Shared layout for all public-facing pages (header, navbar, footer)
// TODO: Implement in Tugas 4
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'SobatBogor' ?></title>
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
    <main><?= $content ?? '' ?></main>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
