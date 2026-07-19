<?php
function formatPriceW(string|int|null $price): string {
    if ($price === null || $price == 0) return '<span class="text-success fw-bold">Gratis</span>';
    return 'Rp ' . number_format((float)$price, 0, ',', '.');
}
?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#0f172a,#1e293b);padding:3.5rem 0 2.5rem;">
    <div class="container">
        <h1 style="color:#fff;font-weight:800;font-size:2rem;margin-bottom:0.4rem;">
            <i class="fas fa-heart me-2" style="color:#ef4444;"></i>Wishlist Saya
        </h1>
        <p style="color:rgba(255,255,255,0.65);font-size:0.95rem;">
            <?= count($items) ?> destinasi tersimpan
        </p>
    </div>
</div>

<div class="container py-5">

    <?php if (empty($items)): ?>
    <!-- Empty State -->
    <div class="text-center py-5" data-aos="fade-up">
        <div style="width:100px;height:100px;border-radius:50%;background:var(--gray-100);margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;">
            <i class="far fa-heart" style="font-size:2.5rem;color:var(--gray-500);"></i>
        </div>
        <h2 style="font-weight:700;color:var(--dark);font-size:1.5rem;">Wishlist Masih Kosong</h2>
        <p style="color:var(--gray-500);max-width:420px;margin:0.5rem auto 2rem;">
            Jelajahi destinasi wisata Bogor dan simpan favoritmu dengan menekan ikon hati.
        </p>
        <a href="<?= BASE_URL ?>/destinations" class="btn-primary-custom btn px-4">
            <i class="fas fa-compass me-2"></i>Jelajahi Wisata
        </a>
    </div>

    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($items as $idx => $item): ?>
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($idx % 4) * 70 ?>">
            <div class="destination-card" id="wishcard-<?= $item['destination_id'] ?>">
                <div class="card-img-wrapper">
                    <?php if ($item['primary_image']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($item['primary_image']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
                    <?php else: ?>
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#fed7aa,#fb923c);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-mountain" style="font-size:2.5rem;color:rgba(255,255,255,0.5);"></i>
                    </div>
                    <?php endif; ?>

                    <span class="badge-category"><?= htmlspecialchars($item['category_name'] ?? 'Wisata') ?></span>

                    <!-- Remove from wishlist -->
                    <button class="wishlist-btn active"
                            onclick="removeFromWishlist(<?= $item['destination_id'] ?>, this)"
                            title="Hapus dari Wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div class="card-body d-flex flex-column">
                    <h3 class="card-title">
                        <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($item['slug']) ?>">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                    </h3>
                    <p class="card-address">
                        <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>
                        <?= htmlspecialchars($item['address'] ?? 'Bogor') ?>
                    </p>
                    <?php if ($item['open_hours']): ?>
                    <?php $status = getDestinationStatus($item['open_hours']); ?>
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge" style="<?= $status['style'] ?>; font-size:0.7rem; padding:0.22rem 0.5rem; border-radius:30px; font-weight:600;">
                            <?= $status['label'] ?>
                        </span>
                        <span style="font-size:0.76rem; color:var(--gray-500); font-family:'Outfit',sans-serif;">
                            <i class="fas fa-clock me-1" style="color:var(--gray-400);"></i><?= htmlspecialchars($item['open_hours']) ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <div class="card-footer-info mt-auto">
                        <div class="ticket-price"><?= formatPriceW($item['ticket_price']) ?></div>
                        <div class="rating-stars">
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.72rem;"></i>
                            <span style="color:var(--gray-500);font-size:0.78rem;"><?= $item['avg_rating'] ?: '–' ?></span>
                        </div>
                    </div>

                    <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($item['slug']) ?>"
                       class="btn-primary-custom btn w-100 mt-3" style="justify-content:center;font-size:0.9rem;">
                        <i class="fas fa-map-signs me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function removeFromWishlist(destinationId, btn) {
    Swal.fire({
        title: 'Hapus dari Wishlist?',
        text: 'Destinasi ini akan dihapus dari daftar wishlist kamu.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('<?= BASE_URL ?>/wishlist/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'destination_id=' + destinationId
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('wishcard-' + destinationId);
                card.style.transition = 'all 0.4s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    card.closest('.col-lg-3').remove();
                    // Update counter
                    const remaining = document.querySelectorAll('[id^="wishcard-"]').length;
                    if (remaining === 0) location.reload(); // show empty state
                }, 400);
                Swal.fire({ toast:true, position:'top-end', icon:'success',
                            title:'Dihapus dari Wishlist', showConfirmButton:false, timer:1800 });
            }
        });
    });
}
</script>


