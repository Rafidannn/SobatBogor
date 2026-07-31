<footer class="footer-custom">
    <div class="container">
        <div class="row g-4">
            <!-- Brand & About -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand d-flex align-items-center gap-2 mb-3">
                    <img src="<?= BASE_URL ?>/assets/img/bulet.png"
                         alt="SobatBogor Logo"
                         style="height:44px;width:44px;object-fit:contain;filter:brightness(1.1);">
                    <img src="<?= BASE_URL ?>/assets/img/textkebogoran_light.png"
                         alt="Sobat Bogor"
                         style="height:30px;object-fit:contain;max-width:155px;">
                </div>
                <p style="font-size:0.9rem;line-height:1.7;">
                    Platform wisata digital untuk menemukan, menyimpan, dan menjelajahi keindahan destinasi di Kota & Kabupaten Bogor.
                </p>
                <!-- Socials -->
                <div class="d-flex gap-2 mt-3">
                    <?php
                    $socials = [
                        ['icon' => 'fab fa-instagram', 'url' => '#', 'label' => 'Instagram'],
                        ['icon' => 'fab fa-tiktok',    'url' => '#', 'label' => 'TikTok'],
                        ['icon' => 'fab fa-youtube',   'url' => '#', 'label' => 'YouTube'],
                    ];
                    foreach ($socials as $s): ?>
                    <a href="<?= $s['url'] ?>" aria-label="<?= $s['label'] ?>"
                       style="width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,0.08);display:inline-flex;align-items:center;justify-content:center;transition:var(--transition);"
                       onmouseover="this.style.background='var(--primary)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <i class="<?= $s['icon'] ?>" style="font-size:0.9rem;color:#fff;"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Destinasi Populer -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6>Jelajahi</h6>
                <a href="<?= BASE_URL ?>/destinations">Semua Destinasi</a>
                <a href="<?= BASE_URL ?>/destinations?category=alam">Wisata Alam</a>
                <a href="<?= BASE_URL ?>/destinations?category=kuliner">Wisata Kuliner</a>
                <a href="<?= BASE_URL ?>/destinations?category=budaya">Wisata Budaya</a>
                <a href="<?= BASE_URL ?>/destinations?category=keluarga">Wisata Keluarga</a>
            </div>

            <!-- Akun -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6>Akun</h6>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/wishlist">Wishlist Saya</a>
                <a href="<?= BASE_URL ?>/logout">Keluar</a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/login">Masuk</a>
                <a href="<?= BASE_URL ?>/register">Daftar Akun</a>
                <?php endif; ?>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 col-md-6">
                <h6>Kontak</h6>
                <div class="d-flex align-items-start gap-2 mb-2" style="font-size:0.88rem;">
                    <i class="fas fa-map-marker-alt mt-1" style="color:var(--primary);"></i>
                    <span>Jl. Contoh No. 1, Bogor, Jawa Barat 16111</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2" style="font-size:0.88rem;">
                    <i class="fas fa-envelope" style="color:var(--primary);"></i>
                    <span>hello@sobatbogor.id</span>
                </div>
                <div class="d-flex align-items-center gap-2" style="font-size:0.88rem;">
                    <i class="fas fa-phone" style="color:var(--primary);"></i>
                    <span>+62 251 123 4567</span>
                </div>
            </div>
        </div>

        <!-- Divider & Copyright -->
        <hr class="footer-divider">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0" style="font-size:0.85rem;">
                &copy; <?= date('Y') ?> <strong style="color:var(--primary);">SobatBogor</strong>. Hak cipta dilindungi.
            </p>
            <p class="mb-0" style="font-size:0.82rem;">
                Dibuat dengan <i class="fas fa-heart text-danger mx-1"></i> untuk pariwisata Bogor
            </p>
        </div>
    </div>
</footer>
