<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | <?= htmlspecialchars($title ?? 'SobatBogor') ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/bulet.png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f0f4f8;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: linear-gradient(180deg, #0d1b35 0%, #0a2515 100%);
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        #sidebar .sidebar-header {
            padding: 20px 20px 16px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #sidebar .sidebar-header .admin-badge {
            font-size: 0.7rem;
            background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
            color: #fff;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 6px;
        }

        #sidebar ul.components {
            padding: 16px 0;
        }

        #sidebar ul li a {
            padding: 11px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            font-weight: 500;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-left: 3px solid #3a9e3a;
        }

        #sidebar ul li.active>a {
            background: rgba(26, 107, 191, 0.2);
            border-left: 3px solid #1a6bbf;
        }

        #sidebar .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 8px 16px;
        }

        #content {
            width: 100%;
            padding: 24px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .admin-topnav {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 10px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-topnav .page-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: #0f172a;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include ROOT_PATH . '/app/views/partials/admin_sidebar.php'; ?>

        <div id="content">
            <!-- Navbar Atas Admin -->
            <div class="admin-topnav">
                <span class="page-title">
                    <i class="fa fa-th-large me-2" style="color:#1a6bbf;"></i>
                    <?= htmlspecialchars($title ?? 'Dashboard') ?>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= BASE_URL ?>/" class="btn btn-sm btn-outline-secondary rounded-pill"
                        style="font-size:0.85rem;">
                        <i class="fa fa-external-link-alt me-1"></i>Lihat Website
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3"
                            style="border:1.5px solid #e2e8f0;background:#fff;" data-bs-toggle="dropdown">
                            <span
                                style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#1a6bbf,#3a9e3a);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                            </span>
                            <span
                                style="font-weight:600;font-size:0.9rem;color:#0f172a;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                            <i class="fa fa-chevron-down" style="font-size:0.7rem;color:#64748b;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                            style="border-radius:12px;min-width:180px;">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/"><i
                                        class="fa fa-home me-2 text-primary"></i>Beranda Web</a></li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout"><i
                                        class="fa fa-sign-out-alt me-2"></i>Keluar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Pesan Notifikasi Global (Flash Message) -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i> <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Konten Utama -->
            <?= $content ?? '' ?>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>