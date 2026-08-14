<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Page Header / Hero -->
<div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 3rem 0 2rem; color: #fff;">
    <div class="container">
        <div class="d-flex align-items-center gap-2 mb-2 text-white-50 small">
            <a href="<?= BASE_URL ?>/hotels" class="text-white-50 text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Katalog Hotel</a>
            <span>/</span>
            <span class="text-white"><?= htmlspecialchars($hotel['name']) ?></span>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1" style="font-size:0.75rem;">
                        <i class="fas fa-map-marker-alt me-1"></i>Dekat <?= htmlspecialchars($hotel['destination_name']) ?>
                    </span>
                    <span class="text-warning">
                        <?php for ($s = 1; $s <= $hotel['star_rating']; $s++): ?>★<?php endfor; ?>
                    </span>
                </div>
                <h1 class="h2 fw-bold text-white mb-1"><?= htmlspecialchars($hotel['name']) ?></h1>
                <p class="text-white-50 small mb-0"><i class="fas fa-map-pin me-1"></i><?= htmlspecialchars($hotel['address'] ?? 'Bogor, Jawa Barat') ?></p>
            </div>

            <!-- Price & Booking Quick CTA -->
            <div class="text-md-end bg-white p-3 rounded-3 shadow-sm text-dark" style="min-width:220px;">
                <div style="font-size:0.75rem;color:var(--gray-500);">Harga mulai dari</div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--primary);line-height:1.1;">
                    Rp <?= number_format($hotel['price_start'], 0, ',', '.') ?>
                    <span style="font-size:0.75rem;font-weight:400;color:var(--gray-500);">/malam</span>
                </div>
                <div class="d-flex gap-2 mt-2">
                    <?php if (!empty($hotel['traveloka_url'])): ?>
                    <a href="<?= htmlspecialchars($hotel['traveloka_url']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-primary w-100 rounded-pill">
                        Pesan di Traveloka
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($hotel['booking_url'])): ?>
                    <a href="<?= htmlspecialchars($hotel['booking_url']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark w-100 rounded-pill">
                        Booking.com
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Column (Left) -->
        <div class="col-lg-8">
            <!-- Hotel Main Photo -->
            <div class="mb-4 rounded-4 overflow-hidden shadow-sm" style="max-height:420px;background:#f1f5f9;">
                <?php if (!empty($hotel['image_path'])): ?>
                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($hotel['image_path']) ?>" alt="<?= htmlspecialchars($hotel['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                <div class="py-5 text-center text-muted">
                    <i class="fas fa-hotel fa-4x opacity-25 mb-2"></i>
                    <p>Foto belum tersedia</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <?php if (!empty($hotel['description'])): ?>
            <div class="card border-0 shadow-sm mb-4 p-4" style="border-radius:16px;">
                <h3 class="h5 fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Tentang Hotel</h3>
                <p class="text-secondary" style="line-height:1.7;font-size:0.95rem;"><?= nl2br(htmlspecialchars($hotel['description'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Facilities List -->
            <?php if (!empty($hotel['facilities'])): ?>
            <?php $facList = array_map('trim', explode(',', $hotel['facilities'])); ?>
            <div class="card border-0 shadow-sm mb-4 p-4" style="border-radius:16px;">
                <h3 class="h5 fw-bold mb-3"><i class="fas fa-concierge-bell text-success me-2"></i>Fasilitas Utama</h3>
                <div class="row g-3">
                    <?php foreach ($facList as $fac): ?>
                    <div class="col-sm-6 col-md-4">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="fw-semibold text-dark small"><?= htmlspecialchars($fac) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Virtual Tour (Video) -->
            <?php if (!empty($hotel['video_url'])): ?>
            <?php $videoId = getYouTubeVideoId($hotel['video_url']); ?>
            <?php if ($videoId): ?>
            <div class="card border-0 shadow-sm mb-4 p-4" style="border-radius:16px;">
                <h3 class="h5 fw-bold mb-3"><i class="fab fa-youtube text-danger me-2"></i>Virtual Tour & Ulasan Hotel</h3>
                <div class="ratio ratio-16x9 overflow-hidden rounded-3 shadow-sm">
                    <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>" 
                            title="Virtual Tour Hotel" 
                            allowfullscreen 
                            style="border:0;"></iframe>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Location Map -->
            <?php if ($hotel['latitude'] && $hotel['longitude']): ?>
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
                <h3 class="h5 fw-bold mb-3"><i class="fas fa-map-marked-alt text-primary me-2"></i>Lokasi Peta</h3>
                <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($hotel['address'] ?? '') ?></p>
                <div id="hotelDetailMap" style="width:100%;height:320px;border-radius:12px;overflow:hidden;"></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Column (Right) -->
        <div class="col-lg-4">
            <!-- Destinasi Wisata Terdekat -->
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius:16px;">
                <h3 class="h6 fw-bold mb-3"><i class="fas fa-compass text-primary me-2"></i>Wisata Terdekat</h3>
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-2" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <div style="width:42px;height:42px;border-radius:10px;background:#3a9e3a;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark small mb-1"><?= htmlspecialchars($hotel['destination_name']) ?></div>
                        <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($hotel['destination_slug']) ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1" style="font-size:0.72rem;">
                            Lihat Destinasi Wisata
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hotel Lain di Area Sama -->
            <?php if (!empty($otherHotels)): ?>
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
                <h3 class="h6 fw-bold mb-3"><i class="fas fa-building text-info me-2"></i>Rekomendasi Lain</h3>
                <?php foreach ($otherHotels as $oh): ?>
                <a href="<?= BASE_URL ?>/hotels/<?= $oh['id'] ?>" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-3 p-2 mb-2 rounded-3" style="background:#fff;border:1px solid #e2e8f0;transition:transform 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <?php if ($oh['image_path']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($oh['image_path']) ?>" style="width:50px;height:50px;border-radius:8px;object-fit:cover;flex-shrink:0;">
                        <?php else: ?>
                        <div style="width:50px;height:50px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-hotel text-muted"></i>
                        </div>
                        <?php endif; ?>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark small text-truncate"><?= htmlspecialchars($oh['name']) ?></div>
                            <div class="text-primary fw-semibold" style="font-size:0.75rem;">Rp <?= number_format($oh['price_start'], 0, ',', '.') ?>/malam</div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<?php if ($hotel['latitude'] && $hotel['longitude']): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const lat = <?= (float)$hotel['latitude'] ?>;
    const lng = <?= (float)$hotel['longitude'] ?>;
    const map = L.map('hotelDetailMap').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
     .bindPopup("<b><?= htmlspecialchars($hotel['name']) ?></b><br><?= htmlspecialchars($hotel['address'] ?? '') ?>").openPopup();
});
</script>
<?php endif; ?>
