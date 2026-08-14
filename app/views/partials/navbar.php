<?php
// Deteksi halaman aktif untuk nav-link.active
$_rawPath    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$_basePath   = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
$_basePath   = rtrim($_basePath, '/');
$currentPath = ($_basePath !== '' && str_starts_with($_rawPath, $_basePath))
               ? substr($_rawPath, strlen($_basePath))
               : $_rawPath;
if ($currentPath === '' || $currentPath === false) $currentPath = '/';
$isLoggedIn  = isset($_SESSION['user_id']);
$isAdmin     = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userName    = $_SESSION['user_name'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-custom sticky-top" id="mainNavbar">
    <div class="container">

        <!-- Brand / Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>/">
            <img src="<?= BASE_URL ?>/assets/img/bulet.png"
                 alt="SobatBogor Logo"
                 style="height:42px;width:42px;object-fit:contain;">
            <span style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.25rem;line-height:1;letter-spacing:-0.5px;">
                <span style="color:#00529E;">Sobat</span><span style="color:#528934;">Bogor</span>
            </span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPath === '/' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/">
                        <i class="fas fa-home me-1 d-lg-none"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/destinations') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/destinations">
                        <i class="fas fa-map-marked-alt me-1 d-lg-none"></i>Wisata
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/kuliner') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/kuliner">
                        <i class="fas fa-utensils me-1 d-lg-none"></i>Kuliner
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/hotels') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/hotels">
                        <i class="fas fa-hotel me-1 d-lg-none"></i>Penginapan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/peta') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/peta">
                        <i class="fas fa-map-marked-alt me-1 d-lg-none"></i>Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/itinerary') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/itinerary">
                        <i class="fas fa-route me-1 d-lg-none"></i>Itinerary
                    </a>
                </li>

                <?php if ($isLoggedIn): ?>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($currentPath, '/wishlist') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/wishlist">
                        <i class="fas fa-heart me-1"></i>Wishlist
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Right Side -->
            <div class="d-flex align-items-center gap-2">
                <?php if ($isLoggedIn): ?>
                    <?php if ($isAdmin): ?>
                    <a href="<?= BASE_URL ?>/admin" class="btn btn-sm btn-outline-warning rounded-pill fw-600">
                        <i class="fas fa-shield-alt me-1"></i>Admin
                    </a>
                    <?php endif; ?>

                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3"
                                style="border:1.5px solid var(--gray-200);background:var(--white);"
                                data-bs-toggle="dropdown">
                            <span style="width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">
                                <?= strtoupper(substr($userName, 0, 1)) ?>
                            </span>
                            <span class="fw-600 d-none d-md-inline" style="font-size:0.9rem;color:var(--dark);">
                                <?= htmlspecialchars(explode(' ', $userName)[0]) ?>
                            </span>
                            <i class="fas fa-chevron-down" style="font-size:0.7rem;color:var(--gray-500);"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius:12px;min-width:180px;">
                            <li><span class="dropdown-item-text text-muted" style="font-size:0.8rem;"><?= htmlspecialchars($userName) ?></span></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/itinerary/builder">
                                    <i class="fas fa-tools me-2 text-success"></i>Itinerary Saya (Builder)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/wishlist">
                                    <i class="fas fa-heart me-2 text-danger"></i>Wishlist Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/my-reviews">
                                    <i class="fas fa-comments me-2 text-primary"></i>Ulasan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="btn-secondary-custom btn btn-sm">Masuk</a>
                    <a href="<?= BASE_URL ?>/register" class="btn-primary-custom btn btn-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
    // Navbar shadow on scroll
    window.addEventListener('scroll', function () {
        const nav = document.getElementById('mainNavbar');
        if (window.scrollY > 20) {
            nav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.08)';
        } else {
            nav.style.boxShadow = '0 1px 0 #e2e8f0';
        }
    });
</script>
