<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<nav id="sidebar">
    <div class="sidebar-header">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" style="text-decoration:none;display:flex;align-items:center;gap:10px;">
            <img src="<?= BASE_URL ?>/assets/img/bulet.png"
                 alt="SobatBogor"
                 style="height:40px;width:40px;object-fit:contain;">
            <img src="<?= BASE_URL ?>/assets/img/textkebogoran.png"
                 alt="Sobat Bogor"
                 style="height:22px;object-fit:contain;max-width:120px;filter:brightness(0) invert(1);">
        </a>
        <div class="admin-badge mt-2">
            <i class="fa fa-shield-alt me-1"></i> Panel Administrator
        </div>
    </div>

    <ul class="list-unstyled components">
        <li style="padding: 8px 16px 4px; font-size:0.7rem; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:1px; font-weight:600;">
            MENU UTAMA
        </li>
        <li class="<?= $currentPath === '/admin' ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin">
                <i class="fa fa-tachometer-alt" style="width:18px;text-align:center;"></i>
                Dashboard
            </a>
        </li>
        <li class="<?= str_starts_with($currentPath, '/admin/destinations') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/destinations">
                <i class="fa fa-map-marker-alt" style="width:18px;text-align:center;"></i>
                Destinasi Wisata
            </a>
        </li>
        <li class="<?= str_starts_with($currentPath, '/admin/categories') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/categories">
                <i class="fa fa-tags" style="width:18px;text-align:center;"></i>
                Kategori
            </a>
        </li>
        <li class="<?= str_starts_with($currentPath, '/admin/reviews') ? 'active' : '' ?>">
            <a href="<?= BASE_URL ?>/admin/reviews">
                <i class="fa fa-comments" style="width:18px;text-align:center;"></i>
                Moderasi Ulasan
            </a>
        </li>

        <hr class="sidebar-divider">

        <li style="padding: 8px 16px 4px; font-size:0.7rem; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:1px; font-weight:600;">
            LAINNYA
        </li>
        <li>
            <a href="<?= BASE_URL ?>/" style="color:rgba(110,231,183,0.8)!important;">
                <i class="fa fa-arrow-left" style="width:18px;text-align:center;"></i>
                Kembali ke Website
            </a>
        </li>
    </ul>
</nav>
