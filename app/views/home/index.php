<?php
// Helper: format harga tiket
function formatPrice(string|int|null $price): string {
    if ($price === null || $price == 0) return '<span class="text-success fw-600">Gratis</span>';
    return '<span>Rp ' . number_format((float)$price, 0, ',', '.') . '</span>';
}
// Helper: render bintang rating
function renderStars(float $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= round($rating)
            ? '<i class="fas fa-star" style="color:#f59e0b;font-size:0.75rem;"></i>'
            : '<i class="far fa-star" style="color:#d1d5db;font-size:0.75rem;"></i>';
    }
    return $html;
}
?>

<!-- ══════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════ -->
<section class="hero-section">
    <!-- Overlay background gradient -->
    <div class="hero-bg" style="background-image: url('<?= BASE_URL ?>/assets/img/hero-bg.jpg');"></div>
    <!-- Decorative blobs -->
    <div style="position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:rgba(234,88,12,0.15);filter:blur(80px);pointer-events:none;"></div>

    <div class="container hero-content py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <!-- Label pill -->
                <div class="d-inline-flex align-items-center gap-2 mb-3"
                     style="background:rgba(26,107,191,0.2);border:1px solid rgba(58,158,58,0.4);border-radius:50px;padding:0.35rem 1rem;">
                    <span style="width:8px;height:8px;border-radius:50%;background:linear-gradient(135deg,#1a6bbf,#3a9e3a);display:inline-block;"></span>
                    <span style="color:#93c5fd;font-size:0.85rem;font-weight:600;">Platform Wisata #1 Bogor</span>
                </div>

                <h1 class="hero-title" data-aos="fade-up">
                    Jelajahi Keindahan<br>
                    <span class="accent">Wisata Bogor</span><br>
                    Bersama Kami
                </h1>
                <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Temukan ratusan destinasi wisata terbaik — dari pegunungan sejuk, kuliner lezat, hingga budaya bersejarah di Kota Hujan.
                </p>

                <!-- Search Bar -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <form action="<?= BASE_URL ?>/destinations" method="GET" class="search-bar-wrapper">
                        <i class="fas fa-search" style="color:var(--gray-500);font-size:1rem;"></i>
                        <input type="text" name="q"
                               placeholder="Cari destinasi wisata di Bogor..."
                               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                               autocomplete="off">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search me-1"></i> Cari Wisata
                        </button>
                    </form>
                    <p class="mt-2" style="color:rgba(255,255,255,0.55);font-size:0.82rem;">
                        <i class="fas fa-fire me-1" style="color:#fb923c;"></i>
                        Trending: Kebun Raya Bogor, Puncak, Gunung Salak
                    </p>
                </div>

                <!-- Quick stats row -->
                <div class="d-flex flex-wrap gap-3 mt-4" data-aos="fade-up" data-aos-delay="300">
                    <?php $heroStats = [
                        ['val' => $stats['destinations'], 'label' => 'Destinasi'],
                        ['val' => $stats['categories'],   'label' => 'Kategori'],
                        ['val' => $stats['reviews'],      'label' => 'Ulasan'],
                    ]; foreach ($heroStats as $hs): ?>
                    <div style="text-align:center;">
                        <div style="font-size:1.6rem;font-weight:800;color:#fff;line-height:1;"><?= $hs['val'] ?>+</div>
                        <div style="font-size:0.78rem;color:rgba(255,255,255,0.6);margin-top:2px;"><?= $hs['label'] ?></div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                    <?php endforeach; ?>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap gap-2 mt-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="<?= BASE_URL ?>/peta" class="btn d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2"
                       style="background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);border:1.5px solid rgba(255,255,255,0.25);color:#fff;font-family:'Outfit',sans-serif;font-weight:600;font-size:0.88rem;transition:all 0.2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                        <i class="fas fa-map-marked-alt" style="color:#fb923c;"></i>
                        Jelajahi Peta Wisata
                    </a>
                    <a href="<?= BASE_URL ?>/itinerary" class="btn d-inline-flex align-items-center gap-2 rounded-pill px-4 py-2"
                       style="background:linear-gradient(135deg, #ea580c, #f97316);color:#fff;font-family:'Outfit',sans-serif;font-weight:600;font-size:0.88rem;box-shadow:0 4px 15px rgba(234,88,12,0.35);transition:all 0.2s;">
                        <i class="fas fa-compass"></i>
                        Buat Itinerary Otomatis
                    </a>
                </div>

            </div>


        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     KATEGORI FILTER PILLS
══════════════════════════════════════════════════════ -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <h2 class="section-title">Jelajahi Berdasarkan Kategori</h2>
            <p class="section-subtitle">Temukan wisata sesuai minat dan suasana yang kamu inginkan</p>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2 category-pills" data-aos="fade-up" data-aos-delay="100">
            <a href="<?= BASE_URL ?>/destinations" class="pill <?= !isset($_GET['category']) ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i> Semua
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/destinations?category=<?= htmlspecialchars($cat['slug']) ?>"
               class="pill">
                <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?>"></i>
                <?= htmlspecialchars($cat['name']) ?>
                <span style="background:rgba(0,0,0,0.08);border-radius:50px;padding:1px 7px;font-size:0.75rem;">
                    <?= $cat['total_destinations'] ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     FEATURED / TRENDING DESTINATIONS
══════════════════════════════════════════════════════ -->
<?php if (!empty($featuredDestinations)): ?>
<section class="py-5" style="background:var(--gray-50);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge-trending"><i class="fas fa-fire me-1"></i>Trending</span>
                </div>
                <h2 class="section-title mb-0">Destinasi Pilihan</h2>
                <p class="section-subtitle mb-0">Tempat wisata paling populer dan direkomendasikan</p>
            </div>
            <a href="<?= BASE_URL ?>/destinations?featured=1" class="btn-secondary-custom btn d-none d-md-inline-flex align-items-center gap-2">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredDestinations as $idx => $dest): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $idx * 80 ?>">
                <div class="destination-card">
                    <div class="card-img-wrapper">
                        <?php if ($dest['primary_image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($dest['primary_image']) ?>"
                             alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy">
                        <?php else: ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#fed7aa,#fb923c);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-mountain" style="font-size:3rem;color:rgba(255,255,255,0.5);"></i>
                        </div>
                        <?php endif; ?>

                        <!-- Category badge -->
                        <span class="badge-category"><?= htmlspecialchars($dest['category_name'] ?? 'Wisata') ?></span>

                        <!-- Status Badge (Buka/Tutup) -->
                        <?php $statusInfo = getDestinationStatus($dest['open_hours'] ?? ''); ?>
                        <span class="badge position-absolute" style="top:44px;left:12px;<?= $statusInfo['style'] ?>;font-size:0.7rem;border-radius:50px;padding:0.25rem 0.65rem;backdrop-filter:blur(4px);z-index:2;">
                            <?= $statusInfo['label'] ?>
                        </span>

                        <!-- Wishlist button -->
                        <?php $isWishlisted = in_array($dest['id'], $wishlistIds); ?>
                        <button class="wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
                                id="wish-btn-<?= $dest['id'] ?>"
                                onclick="toggleWishlist(<?= $dest['id'] ?>, this)"
                                title="<?= $isWishlisted ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' ?>">
                            <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($dest['slug']) ?>">
                                <?= htmlspecialchars($dest['name']) ?>
                            </a>
                        </h3>
                        <p class="card-address">
                            <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>
                            <?= htmlspecialchars($dest['address'] ?? 'Bogor, Jawa Barat') ?>
                        </p>
                        <div class="card-footer-info">
                            <?php $pricing = getDestinationPricing($dest); ?>
                            <div>
                                <div class="ticket-price">
                                    <?= $pricing['formatted_today'] ?> <span style="font-size:0.72rem;color:var(--gray-500);font-weight:normal;">/ orang</span>
                                </div>
                                <div style="font-size:0.72rem;color:var(--gray-500);">
                                    <span title="Harga Hari Kerja (Senin-Jumat)">WD: <?= $pricing['formatted_weekday'] ?></span> | 
                                    <span title="Harga Akhir Pekan (Sabtu-Minggu)">WE: <?= $pricing['formatted_weekend'] ?></span>
                                </div>
                            </div>
                            <div class="rating-stars">
                                <?= renderStars((float)$dest['avg_rating']) ?>
                                <span style="color:var(--gray-500);font-size:0.78rem;margin-left:4px;">
                                    <?= $dest['avg_rating'] ?> (<?= $dest['review_count'] ?>)
                                </span>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($dest['slug']) ?>"
                           class="btn-primary-custom btn w-100 mt-3" style="justify-content:center;">
                            <i class="fas fa-map-signs me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="<?= BASE_URL ?>/destinations" class="btn-secondary-custom btn">Lihat Semua Wisata</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     STATS STRIP
══════════════════════════════════════════════════════ -->
<section class="stats-strip" data-aos="fade-up">
    <div class="container">
        <div class="row g-4 text-center">
            <?php $statItems = [
                ['icon' => 'fas fa-map-marked-alt', 'val' => $stats['destinations'], 'label' => 'Destinasi Wisata'],
                ['icon' => 'fas fa-th-large',       'val' => $stats['categories'],   'label' => 'Kategori Wisata'],
                ['icon' => 'fas fa-star',            'val' => $stats['reviews'],      'label' => 'Ulasan Pengguna'],
                ['icon' => 'fas fa-users',           'val' => $stats['users'],        'label' => 'Wisatawan Terdaftar'],
            ]; ?>
            <?php foreach ($statItems as $s): ?>
            <div class="col-6 col-md-3">
                <i class="<?= $s['icon'] ?>" style="font-size:2rem;opacity:0.7;margin-bottom:0.75rem;"></i>
                <div class="stat-number"><?= $s['val'] ?>+</div>
                <div class="stat-label"><?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     DESTINASI TERBARU
══════════════════════════════════════════════════════ -->
<?php if (!empty($recentDestinations)): ?>
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="section-title mb-0">Wisata Terbaru</h2>
                <p class="section-subtitle mb-0">Destinasi yang baru saja ditambahkan untuk kamu jelajahi</p>
            </div>
            <a href="<?= BASE_URL ?>/destinations" class="btn-secondary-custom btn d-none d-md-inline-flex align-items-center gap-2">
                Semua Destinasi <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($recentDestinations as $idx => $dest): ?>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($idx % 4) * 70 ?>">
                <div class="destination-card">
                    <div class="card-img-wrapper">
                        <?php if ($dest['primary_image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($dest['primary_image']) ?>"
                             alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy">
                        <?php else: ?>
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#dcfce7,#16a34a);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-tree" style="font-size:2.5rem;color:rgba(255,255,255,0.5);"></i>
                        </div>
                        <?php endif; ?>
                        <span class="badge-category"><?= htmlspecialchars($dest['category_name'] ?? 'Wisata') ?></span>
                        <?php $isWishlisted = in_array($dest['id'], $wishlistIds); ?>
                        <button class="wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
                                id="wish-btn-r-<?= $dest['id'] ?>"
                                onclick="toggleWishlist(<?= $dest['id'] ?>, this)"
                                title="Wishlist">
                            <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title" style="font-size:0.95rem;">
                            <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($dest['slug']) ?>">
                                <?= htmlspecialchars($dest['name']) ?>
                            </a>
                        </h3>
                        <p class="card-address" style="font-size:0.78rem;">
                            <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>
                            <?= htmlspecialchars($dest['address'] ?? 'Bogor') ?>
                        </p>
                        <div class="card-footer-info">
                            <?php $pricingRecent = getDestinationPricing($dest); ?>
                            <div>
                                <div class="ticket-price" style="font-size:0.85rem;">
                                    <?= $pricingRecent['formatted_today'] ?>
                                </div>
                                <div style="font-size:0.68rem;color:var(--gray-500);">
                                    WD: <?= $pricingRecent['formatted_weekday'] ?> | WE: <?= $pricingRecent['formatted_weekend'] ?>
                                </div>
                            </div>
                            <div class="rating-stars">
                                <i class="fas fa-star" style="color:#f59e0b;font-size:0.72rem;"></i>
                                <span style="color:var(--gray-500);font-size:0.78rem;"><?= $dest['avg_rating'] ?: '–' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════
     CTA SECTION
══════════════════════════════════════════════════════ -->
<?php if (!isset($_SESSION['user_id'])): ?>
<section class="py-5" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);">
    <div class="container text-center py-3" data-aos="fade-up">
        <h2 style="color:#fff;font-weight:800;font-size:2rem;margin-bottom:0.75rem;">
            Simpan Destinasi Favoritmu!
        </h2>
        <p style="color:rgba(255,255,255,0.7);font-size:1rem;max-width:500px;margin:0 auto 2rem;">
            Daftar gratis dan mulai buat daftar wishlist wisata impianmu di Bogor.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>/register" class="btn-primary-custom btn px-4 py-2">
                <i class="fas fa-user-plus me-2"></i>Daftar Gratis
            </a>
            <a href="<?= BASE_URL ?>/destinations" class="btn" style="border:2px solid rgba(255,255,255,0.3);color:#fff;border-radius:50px;padding:0.65rem 1.75rem;font-weight:600;transition:var(--transition);"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">
                <i class="fas fa-compass me-2"></i>Jelajahi Dulu
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Wishlist Toggle Script -->
<script>
function toggleWishlist(destinationId, btn) {
    <?php if (!isset($_SESSION['user_id'])): ?>
    Swal.fire({
        title: 'Perlu Login',
        text: 'Silakan masuk terlebih dahulu untuk menyimpan wishlist.',
        icon: 'info',
        confirmButtonText: 'Masuk Sekarang',
        confirmButtonColor: '#ea580c',
        showCancelButton: true,
        cancelButtonText: 'Nanti Saja'
    }).then(res => { if (res.isConfirmed) window.location.href = '<?= BASE_URL ?>/login'; });
    return;
    <?php endif; ?>

    const icon = btn.querySelector('i');
    const isActive = btn.classList.contains('active');
    const url = isActive ? '<?= BASE_URL ?>/wishlist/remove' : '<?= BASE_URL ?>/wishlist/add';

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'destination_id=' + destinationId
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.classList.toggle('active');
            icon.classList.toggle('fas');
            icon.classList.toggle('far');
            const msg = isActive ? 'Dihapus dari Wishlist' : 'Ditambahkan ke Wishlist!';
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: msg,
                        showConfirmButton: false, timer: 1800, timerProgressBar: true });
        }
    })
    .catch(() => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Gagal! Coba lagi.',
                    showConfirmButton: false, timer: 2000 });
    });
}
</script>


