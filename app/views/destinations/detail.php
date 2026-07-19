<?php
function formatPriceDetail(string|int|null $price): string {
    if ($price === null || $price == 0) return '<span class="badge bg-success px-3 py-2 fs-6">Gratis</span>';
    return '<strong style="font-size:1.4rem;color:var(--primary);">Rp ' . number_format((float)$price, 0, ',', '.') . '</strong>';
}
function renderStarsDetail(float $rating, string $size = '0.9rem'): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $filled = $i <= round($rating);
        $html .= '<i class="' . ($filled ? 'fas' : 'far') . ' fa-star"'
               . ' style="color:' . ($filled ? '#f59e0b' : '#d1d5db') . ';font-size:' . $size . ';"></i>';
    }
    return $html;
}
?>

<!-- Flash Messages -->
<?php if ($flashMsg): ?>
<div class="container pt-3">
    <div class="alert alert-<?= $flashMsg['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-<?= $flashMsg['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
        <?= htmlspecialchars($flashMsg['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- Breadcrumb -->
<div style="background:var(--gray-100);border-bottom:1px solid var(--gray-200);padding:0.75rem 0;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" style="color:var(--primary);">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/destinations" style="color:var(--primary);">Wisata</a></li>
                <li class="breadcrumb-item active text-truncate" style="max-width:200px;">
                    <?= htmlspecialchars($destination['name']) ?>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        <!-- ── LEFT: Gallery + Deskripsi + Review ───────────── -->
        <div class="col-lg-8">

            <!-- Gallery Swiper -->
            <div class="destination-gallery mb-4" data-aos="fade-up">
                <?php if (!empty($images)): ?>
                <div class="swiper destinationSwiper" style="border-radius:var(--border-radius);overflow:hidden;">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $img): ?>
                        <div class="swiper-slide">
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image_path']) ?>"
                                 alt="<?= htmlspecialchars($destination['name']) ?>"
                                 style="width:100%;height:420px;object-fit:cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <?php else: ?>
                <!-- Placeholder saat tidak ada foto -->
                <div style="height:360px;border-radius:var(--border-radius);background:linear-gradient(135deg,#fed7aa,#fb923c);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;">
                    <i class="fas fa-mountain" style="font-size:5rem;color:rgba(255,255,255,0.5);"></i>
                    <p style="color:rgba(255,255,255,0.7);font-weight:600;">Belum ada foto</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Title & Rating -->
            <div class="mb-4" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <?php if ($destination['category_name']): ?>
                        <span class="badge mb-2 px-3 py-2"
                              style="background:var(--primary-light);color:var(--primary);font-weight:600;border-radius:50px;">
                            <?= htmlspecialchars($destination['category_name']) ?>
                        </span>
                        <?php endif; ?>
                        <h1 style="font-size:1.8rem;font-weight:800;color:var(--dark);line-height:1.2;margin-bottom:0.5rem;">
                            <?= htmlspecialchars($destination['name']) ?>
                        </h1>
                        <p style="color:var(--gray-500);font-size:0.9rem;">
                            <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>
                            <?= htmlspecialchars($destination['address'] ?? 'Bogor, Jawa Barat') ?>
                        </p>
                    </div>

                    <!-- Wishlist Button -->
                    <button id="detailWishBtn"
                            onclick="toggleWishlistDetail(<?= $destination['id'] ?>, this)"
                            class="btn d-flex align-items-center gap-2 px-3 py-2 rounded-pill <?= $isWishlisted ? 'active' : '' ?>"
                            style="border:2px solid <?= $isWishlisted ? '#ef4444' : 'var(--gray-200)' ?>;background:<?= $isWishlisted ? '#fee2e2' : 'var(--white)' ?>;color:<?= $isWishlisted ? '#ef4444' : 'var(--gray-700)' ?>;font-weight:600;transition:var(--transition);">
                        <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart"></i>
                        <span><?= $isWishlisted ? 'Tersimpan' : 'Simpan' ?></span>
                    </button>
                </div>

                <!-- Rating Strip -->
                <?php if (count($reviews) > 0): ?>
                <div class="d-flex align-items-center gap-3 mt-3 p-3"
                     style="background:var(--gray-50);border-radius:12px;border:1px solid var(--gray-200);">
                    <div style="text-align:center;">
                        <div style="font-size:2.5rem;font-weight:800;color:var(--dark);line-height:1;"><?= $avgRating ?></div>
                        <div><?= renderStarsDetail($avgRating, '0.85rem') ?></div>
                        <div style="font-size:0.78rem;color:var(--gray-500);margin-top:2px;"><?= count($reviews) ?> ulasan</div>
                    </div>
                    <div style="width:1px;height:60px;background:var(--gray-200);"></div>
                    <div style="flex:1;">
                        <?php
                        $ratingDist = [5=>0,4=>0,3=>0,2=>0,1=>0];
                        foreach ($reviews as $rv) $ratingDist[(int)$rv['rating']]++;
                        $total = count($reviews);
                        foreach (array_reverse(array_keys($ratingDist), true) as $star): ?>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span style="font-size:0.75rem;color:var(--gray-500);width:10px;"><?= $star ?></span>
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.65rem;"></i>
                            <div style="flex:1;height:6px;background:var(--gray-200);border-radius:3px;">
                                <div style="height:100%;background:#f59e0b;border-radius:3px;width:<?= $total > 0 ? round($ratingDist[$star] / $total * 100) : 0 ?>%;"></div>
                            </div>
                            <span style="font-size:0.75rem;color:var(--gray-500);width:20px;"><?= $ratingDist[$star] ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Deskripsi -->
            <div class="info-card mb-4" data-aos="fade-up">
                <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;">Tentang Destinasi</h2>
                <div style="line-height:1.8;color:var(--gray-700);font-size:0.95rem;">
                    <?= nl2br(htmlspecialchars($destination['description'] ?? 'Deskripsi belum tersedia.')) ?>
                </div>
            </div>

            <!-- Reviews Section -->
            <div data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 style="font-size:1.2rem;font-weight:700;">
                        <i class="fas fa-comments me-2" style="color:var(--primary);"></i>
                        Ulasan Pengunjung
                        <span class="badge ms-1" style="background:var(--primary);color:#fff;border-radius:50px;font-size:0.75rem;"><?= count($reviews) ?></span>
                    </h2>
                </div>

                <?php if (empty($reviews)): ?>
                <div class="text-center py-4" style="background:var(--gray-50);border-radius:var(--border-radius);">
                    <i class="fas fa-comment-slash" style="font-size:2rem;color:var(--gray-500);"></i>
                    <p class="mt-2 mb-0" style="color:var(--gray-500);">Belum ada ulasan. Jadilah yang pertama!</p>
                </div>
                <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:38px;height:38px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0;">
                                <?= strtoupper(substr($review['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:var(--dark);">
                                    <?= htmlspecialchars($review['user_name'] ?? 'Pengguna') ?>
                                </div>
                                <div><?= renderStarsDetail((float)$review['rating'], '0.75rem') ?></div>
                            </div>
                        </div>
                        <span style="font-size:0.78rem;color:var(--gray-500);">
                            <?= date('d M Y', strtotime($review['created_at'] ?? 'now')) ?>
                        </span>
                    </div>
                    <?php if ($review['comment']): ?>
                    <p class="mt-3 mb-0" style="font-size:0.9rem;line-height:1.7;color:var(--gray-700);">
                        <?= htmlspecialchars($review['comment']) ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <!-- Form Tambah Ulasan -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="info-card mt-4">
                    <?php if ($hasReviewed): ?>
                    <div class="text-center py-2">
                        <i class="fas fa-check-circle" style="color:var(--secondary);font-size:1.5rem;"></i>
                        <p class="mt-2 mb-0" style="color:var(--secondary);font-weight:600;">Kamu sudah memberikan ulasan. Terima kasih!</p>
                    </div>
                    <?php else: ?>
                    <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">
                        <i class="fas fa-pen me-2" style="color:var(--primary);"></i>Tulis Ulasan
                    </h3>
                    <form action="<?= BASE_URL ?>/reviews/submit" method="POST">
                        <input type="hidden" name="destination_id" value="<?= $destination['id'] ?>">

                        <!-- Star Rating Input -->
                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:0.88rem;">Rating</label>
                            <div class="d-flex gap-2" id="starInput">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                <label style="cursor:pointer;font-size:1.5rem;color:#d1d5db;transition:var(--transition);">
                                    <input type="radio" name="rating" value="<?= $s ?>" required
                                           style="display:none;" onchange="updateStars(<?= $s ?>)">
                                    <i class="far fa-star star-icon" data-val="<?= $s ?>"></i>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-600" style="font-size:0.88rem;">Komentar (opsional)</label>
                            <textarea name="comment" rows="3" class="form-control"
                                      placeholder="Bagikan pengalamanmu di <?= htmlspecialchars($destination['name']) ?>..."
                                      style="font-family:'Outfit',sans-serif;border-radius:10px;font-size:0.9rem;resize:none;"></textarea>
                        </div>

                        <button type="submit" class="btn-primary-custom btn">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Ulasan
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="text-center p-3 mt-3"
                     style="background:var(--gray-50);border-radius:12px;border:1.5px dashed var(--gray-200);">
                    <p class="mb-2" style="color:var(--gray-700);">Ingin memberikan ulasan?</p>
                    <a href="<?= BASE_URL ?>/login" class="btn-primary-custom btn btn-sm">Masuk Sekarang</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── RIGHT: Info Card + Related ─────────────────── -->
        <div class="col-lg-4">

            <!-- Harga Tiket -->
            <div class="info-card text-center mb-3" data-aos="fade-left">
                <p style="color:var(--gray-500);font-size:0.82rem;margin-bottom:0.25rem;">Harga Tiket Masuk</p>
                <?= formatPriceDetail($destination['ticket_price']) ?>
                <p style="font-size:0.78rem;color:var(--gray-500);margin-top:0.25rem;">per orang</p>
            </div>

            <!-- Info Detail -->
            <div class="info-card" data-aos="fade-left" data-aos-delay="100">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">Informasi Wisata</h3>

                <?php $infoItems = [
                    ['icon' => 'fa-clock',        'label' => 'Jam Buka',   'val' => $destination['open_hours'] ?? 'Tidak tersedia'],
                    ['icon' => 'fa-map-marker-alt','label' => 'Alamat',    'val' => $destination['address'] ?? '-'],
                    ['icon' => 'fa-th-large',      'label' => 'Kategori',  'val' => $destination['category_name'] ?? '-'],
                ]; ?>
                <?php foreach ($infoItems as $item): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas <?= $item['icon'] ?>"></i></div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:2px;"><?= $item['label'] ?></div>
                        <div style="font-size:0.9rem;font-weight:600;color:var(--dark);"><?= htmlspecialchars($item['val']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if ($destination['latitude'] && $destination['longitude']): ?>
                <div class="info-item">
                    <div class="info-icon"><i class="fas fa-location-dot"></i></div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--gray-500);margin-bottom:4px;">Lokasi di Peta</div>
                        <a href="https://maps.google.com/?q=<?= $destination['latitude'] ?>,<?= $destination['longitude'] ?>"
                           target="_blank" class="btn btn-sm px-3"
                           style="background:var(--secondary);color:#fff;border-radius:8px;font-size:0.8rem;font-weight:600;">
                            <i class="fas fa-map me-1"></i>Buka Maps
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Share -->
            <div class="info-card mt-3" data-aos="fade-left" data-aos-delay="150">
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.75rem;">Bagikan</h3>
                <div class="d-flex gap-2 flex-wrap">
                    <?php
                    $pageUrl = BASE_URL . '/destinations/' . $destination['slug'];
                    $shares  = [
                        ['icon'=>'fab fa-whatsapp',  'color'=>'#25D366','url'=>'https://wa.me/?text=' . urlencode($destination['name'] . ' ' . $pageUrl)],
                        ['icon'=>'fab fa-twitter-x', 'color'=>'#000',   'url'=>'https://twitter.com/intent/tweet?url=' . urlencode($pageUrl) . '&text=' . urlencode($destination['name'])],
                        ['icon'=>'fab fa-facebook',  'color'=>'#1877F2','url'=>'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($pageUrl)],
                    ];
                    foreach ($shares as $s): ?>
                    <a href="<?= $s['url'] ?>" target="_blank"
                       style="background:<?= $s['color'] ?>;color:#fff;width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:var(--transition);"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="<?= $s['icon'] ?>"></i>
                    </a>
                    <?php endforeach; ?>
                    <button onclick="copyLink('<?= $pageUrl ?>')"
                            style="background:var(--gray-100);color:var(--gray-700);border:none;width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:0.9rem;cursor:pointer;transition:var(--transition);">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>

            <!-- Destinasi Terkait -->
            <?php if (!empty($related)): ?>
            <div class="mt-3" data-aos="fade-left" data-aos-delay="200">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;">Wisata Terkait</h3>
                <?php foreach ($related as $rel): ?>
                <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($rel['slug']) ?>" class="text-decoration-none">
                    <div class="d-flex gap-3 align-items-center p-3 mb-2 rounded-3"
                         style="background:var(--white);border:1px solid var(--gray-200);transition:var(--transition);"
                         onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateX(3px)'"
                         onmouseout="this.style.borderColor='var(--gray-200)';this.style.transform='translateX(0)'">
                        <?php if ($rel['primary_image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($rel['primary_image']) ?>"
                             style="width:52px;height:52px;border-radius:10px;object-fit:cover;flex-shrink:0;"
                             alt="<?= htmlspecialchars($rel['name']) ?>">
                        <?php else: ?>
                        <div style="width:52px;height:52px;border-radius:10px;background:var(--primary-light);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-mountain" style="color:var(--primary);"></i>
                        </div>
                        <?php endif; ?>
                        <div style="overflow:hidden;">
                            <div style="font-weight:700;font-size:0.88rem;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <?= htmlspecialchars($rel['name']) ?>
                            </div>
                            <div style="font-size:0.78rem;color:var(--gray-500);">
                                <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>Bogor
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Swiper + Scripts -->
<?php ob_start(); ?>
<script>
// Init Swiper Gallery
new Swiper('.destinationSwiper', {
    loop: true,
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation:  { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    autoplay:    { delay: 4000, disableOnInteraction: false },
});

// Star Rating input
function updateStars(val) {
    document.querySelectorAll('.star-icon').forEach(el => {
        const v = parseInt(el.getAttribute('data-val'));
        el.className = v <= val ? 'fas fa-star star-icon' : 'far fa-star star-icon';
        el.style.color = v <= val ? '#f59e0b' : '#d1d5db';
    });
}

// Copy link
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                    title: 'Link disalin!', showConfirmButton: false, timer: 1500 });
    });
}

// Wishlist toggle (detail page)
function toggleWishlistDetail(destinationId, btn) {
    <?php if (!isset($_SESSION['user_id'])): ?>
    Swal.fire({
        title: 'Perlu Login', text: 'Silakan masuk untuk menyimpan wishlist.',
        icon: 'info', confirmButtonText: 'Masuk', confirmButtonColor: '#ea580c',
        showCancelButton: true, cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) window.location.href = '<?= BASE_URL ?>/login'; });
    return;
    <?php endif; ?>

    const isActive = btn.classList.contains('active');
    const url = isActive ? '<?= BASE_URL ?>/wishlist/remove' : '<?= BASE_URL ?>/wishlist/add';

    fetch(url, {
        method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'destination_id=' + destinationId
    }).then(r => r.json()).then(data => {
        if (data.success) {
            btn.classList.toggle('active');
            const icon = btn.querySelector('i');
            const label = btn.querySelector('span');
            icon.classList.toggle('fas'); icon.classList.toggle('far');
            if (btn.classList.contains('active')) {
                btn.style.cssText = 'border:2px solid #ef4444;background:#fee2e2;color:#ef4444;font-weight:600;transition:var(--transition);border-radius:50px;';
                label.textContent = 'Tersimpan';
            } else {
                btn.style.cssText = 'border:2px solid var(--gray-200);background:var(--white);color:var(--gray-700);font-weight:600;transition:var(--transition);border-radius:50px;';
                label.textContent = 'Simpan';
            }
            Swal.fire({ toast: true, position: 'top-end', icon: 'success',
                        title: isActive ? 'Dihapus dari Wishlist' : 'Ditambahkan ke Wishlist!',
                        showConfirmButton: false, timer: 1800, timerProgressBar: true });
        }
    });
}
</script>
<?php $scripts = ob_get_clean(); ?>


