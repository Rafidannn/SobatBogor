<?php
/**
 * app/views/itinerary/index.php
 * Smart Itinerary Planner View — SobatBogor
 */


?>

<!-- Header Banner -->
<div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 3.5rem 0 2.5rem; position: relative;">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" style="color:rgba(255,255,255,0.6);">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:rgba(255,255,255,0.9);">Itinerary Planner</li>
            </ol>
        </nav>
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge px-3 py-2 rounded-pill mb-2" style="background:rgba(234,88,12,0.2);color:#fb923c;font-weight:600;font-size:0.8rem;border:1px solid rgba(251,146,60,0.3);">
                    <i class="fas fa-magic me-1"></i> Perencana Otomatis
                </span>
                <h1 style="color: #fff; font-weight: 800; font-size: 2.2rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-compass text-warning me-2"></i>Smart Itinerary Planner Bogor
                </h1>
                <p style="color: rgba(255,255,255,0.75); font-size: 0.98rem; margin-bottom: 0;">
                    Rencanakan liburan impianmu di Bogor secara otomatis berdasarkan durasi, preferensi tempat, dan estimasi budget.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">

        <!-- ── FORM SELECTION ─────────────────────────────────── -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid var(--gray-200); background: #fff;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                        <i class="fas fa-sliders-h text-primary"></i> Atur Rencana Liburan
                    </h5>
                    <p class="text-muted small mb-0">Pilih opsi di bawah untuk membuat jadwal otomatis</p>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/itinerary" method="GET" id="itineraryForm">
                        <input type="hidden" name="generate" value="1">

                        <!-- 1. Durasi Liburan -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                                <i class="fas fa-calendar-day text-warning"></i> 1. Durasi Liburan
                            </label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur1" value="1" <?= $duration == 1 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary w-100 py-2 rounded-3 text-center" for="dur1" style="font-size:0.82rem; font-weight:600;">
                                        1 Hari
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur2" value="2" <?= $duration == 2 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary w-100 py-2 rounded-3 text-center" for="dur2" style="font-size:0.82rem; font-weight:600;">
                                        2H 1M
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur3" value="3" <?= $duration == 3 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary w-100 py-2 rounded-3 text-center" for="dur3" style="font-size:0.82rem; font-weight:600;">
                                        3H 2M
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Budget Tier -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                                <i class="fas fa-wallet text-success"></i> 2. Estimasi Budget
                            </label>
                            <div class="d-flex flex-column gap-2">
                                <label class="border rounded-3 p-2.5 d-flex align-items-center justify-content-between cursor-pointer style-budget-label" style="font-size:0.85rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="radio" name="budget" value="ekonomis" <?= $budget === 'ekonomis' ? 'checked' : '' ?>>
                                        <div>
                                            <div class="fw-bold text-dark">Ekonomis</div>
                                            <div class="text-muted" style="font-size:0.75rem;">Hemat & Terjangkau</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Low</span>
                                </label>
                                <label class="border rounded-3 p-2.5 d-flex align-items-center justify-content-between cursor-pointer style-budget-label" style="font-size:0.85rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="radio" name="budget" value="standar" <?= $budget === 'standar' ? 'checked' : '' ?>>
                                        <div>
                                            <div class="fw-bold text-dark">Standar (Rekomendasi)</div>
                                            <div class="text-muted" style="font-size:0.75rem;">Seimbang & Nyaman</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary">Medium</span>
                                </label>
                                <label class="border rounded-3 p-2.5 d-flex align-items-center justify-content-between cursor-pointer style-budget-label" style="font-size:0.85rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="radio" name="budget" value="mewah" <?= $budget === 'mewah' ? 'checked' : '' ?>>
                                        <div>
                                            <div class="fw-bold text-dark">Mewah / Premium</div>
                                            <div class="text-muted" style="font-size:0.75rem;">Fasilitas & Hotel Terbaik</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning">High</span>
                                </label>
                            </div>
                        </div>

                        <!-- 3. Kategori Preferensi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark d-flex align-items-center gap-1">
                                <i class="fas fa-heart text-danger"></i> 3. Preferensi Tempat (Opsional)
                            </label>
                            <div class="d-flex flex-wrap gap-1.5">
                                <?php foreach ($categories as $cat): ?>
                                <?php $isChecked = in_array($cat['slug'], $selectedCats); ?>
                                <input type="checkbox" class="btn-check" name="categories[]" id="cat-<?= $cat['id'] ?>" value="<?= htmlspecialchars($cat['slug']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <label class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5" for="cat-<?= $cat['id'] ?>" style="font-size:0.78rem;">
                                    <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?> me-1"></i>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary-custom btn w-100 py-2.5 rounded-3 fw-bold" style="justify-content:center;">
                            <i class="fas fa-magic me-2"></i>Buat Itinerary Otomatis
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── GENERATED ITINERARY RESULT ────────────────────────── -->
        <div class="col-lg-8">
            <?php if (!$hasGenerated || empty($itinerary)): ?>
            <!-- Empty State / Introduction -->
            <div class="card border-0 shadow-sm p-4 p-md-5 text-center" style="border-radius: 20px; background: #fff;">
                <div class="py-4">
                    <div style="width:100px; height:100px; background:var(--primary-light); border-radius:50%; margin:0 auto 1.5rem; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-route fa-3x" style="color:var(--primary);"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Siap untuk Liburan ke Bogor?</h3>
                    <p class="text-muted mx-auto" style="max-width: 480px; font-size: 0.95rem;">
                        Pilih durasi liburan, estimasi budget, dan kategori tempat favoritmu di menu sebelah kiri, lalu klik <strong>"Buat Itinerary Otomatis"</strong>.
                    </p>

                    <div class="row g-3 mt-4 text-start">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light h-100 border">
                                <div class="text-primary fw-bold mb-1"><i class="fas fa-clock me-1"></i> Hemat Waktu</div>
                                <div class="text-muted small">Susunan jam operasional & rute perjalanan sudah diatur efisien.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light h-100 border">
                                <div class="text-success fw-bold mb-1"><i class="fas fa-calculator me-1"></i> Transparan</div>
                                <div class="text-muted small">Estimasi total biaya tiket, hotel, dan konsumsi dihitung langsung.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light h-100 border">
                                <div class="text-warning fw-bold mb-1"><i class="fas fa-share-alt me-1"></i> Mudah Dibagi</div>
                                <div class="text-muted small">Bisa langsung dicetak atau dibagikan ke keluarga via WhatsApp.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <!-- Generated Result Container -->
            <div id="printableItinerary">
                
                <!-- Action Bar -->
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 print-hide">
                    <div>
                        <h4 class="fw-bold text-dark mb-0"><i class="fas fa-sparkles text-warning me-2"></i>Rencana Perjalanan Liburanmu</h4>
                        <span class="text-muted small">Estimasi untuk <?= $itinerary['duration'] ?> Hari • Budget <?= ucfirst($itinerary['budget_tier']) ?></span>
                    </div>
                    <div class="d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                            <i class="fas fa-print me-1"></i> Cetak / PDF
                        </button>
                        <?php 
                        $waText = "Halo! Ini Rencana Liburan ke Bogor saya (" . $itinerary['duration'] . " Hari): " . BASE_URL . "/itinerary?" . http_build_query($_GET);
                        $waUrl  = "https://api.whatsapp.com/send?text=" . urlencode($waText);
                        ?>
                        <a href="<?= $waUrl ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                            <i class="fab fa-whatsapp me-1"></i> Bagikan WA
                        </a>
                        <a href="<?= BASE_URL ?>/peta" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold">
                            <i class="fas fa-map-marked-alt me-1"></i> Peta
                        </a>
                    </div>
                </div>

                <!-- Summary Budget Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <span class="badge bg-primary-subtle text-primary mb-2">Estimasi Total Biaya / Orang</span>
                                <h2 class="fw-bold mb-1" style="color: #38bdf8; font-size: 2rem;">
                                    Rp <?= number_format($itinerary['grand_total'], 0, ',', '.') ?>
                                </h2>
                                <p class="text-white-50 small mb-0">Rincian mencakup perkiraan tiket tempat wisata, penginapan per malam, dan konsumsi harian.</p>
                            </div>
                            <div class="col-md-5 mt-3 mt-md-0 border-start border-secondary ps-md-4">
                                <div class="d-flex justify-content-between mb-1 small text-white-50">
                                    <span>Tiket Masuk Wisata:</span>
                                    <strong class="text-white">Rp <?= number_format($itinerary['total_ticket'], 0, ',', '.') ?></strong>
                                </div>
                                <?php if ($itinerary['total_hotel'] > 0): ?>
                                <div class="d-flex justify-content-between mb-1 small text-white-50">
                                    <span>Penginapan / Hotel:</span>
                                    <strong class="text-white">Rp <?= number_format($itinerary['total_hotel'], 0, ',', '.') ?></strong>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between small text-white-50">
                                    <span>Estimasi Makan & Jajan:</span>
                                    <strong class="text-white">Rp <?= number_format($itinerary['total_meal'], 0, ',', '.') ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Per-Hari -->
                <div class="d-flex flex-column gap-4">
                    <?php foreach ($itinerary['days'] as $day): ?>
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; border: 1px solid var(--gray-200); overflow: hidden; background: #fff;">
                        <div class="card-header py-3 px-4 d-flex align-items-center justify-content-between" style="background: #0f172a; color: #fff; border-bottom: none;">
                            <h5 class="fw-bold mb-0" style="font-size: 1.05rem;"><i class="fas fa-calendar-alt text-primary me-2"></i>HARI KE-<?= $day['day_number'] ?></h5>
                            <span class="badge bg-white text-dark rounded-pill px-3 fw-bold" style="font-size:0.75rem;">Agenda Harian</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">

                                <!-- Slot 1: Pagi -->
                                <?php if ($day['pagi']): ?>
                                <div class="d-flex gap-3 mb-4 align-items-start">
                                    <div class="badge-time bg-warning-subtle text-warning fw-bold rounded-pill px-3 py-1 text-center" style="font-size:0.78rem; min-width:110px;">
                                        <i class="fas fa-sun me-1"></i>08:00 - 11:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-warning ps-3">
                                        <div class="text-muted small fw-bold uppercase">WISATA PAGI</div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['pagi']['slug'] ?>" class="text-decoration-none text-dark" target="_blank">
                                                <?= htmlspecialchars($day['pagi']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($day['pagi']['address'] ?? 'Bogor') ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border"><?= formatRupiah($day['pagi']['ticket_price_weekday'] ?? $day['pagi']['ticket_price']) ?></span>
                                            <span class="badge bg-light text-dark border">⭐ <?= $day['pagi']['avg_rating'] ?></span>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['pagi']['latitude'] ?>,<?= $day['pagi']['longitude'] ?>" target="_blank" class="text-primary small text-decoration-none fw-semibold">
                                                <i class="fas fa-directions me-1"></i>Petunjuk Arah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Slot 2: Makan Siang / Kuliner -->
                                <div class="d-flex gap-3 mb-4 align-items-start">
                                    <div class="badge-time bg-danger-subtle text-danger fw-bold rounded-pill px-3 py-1 text-center" style="font-size:0.78rem; min-width:110px;">
                                        <i class="fas fa-utensils me-1"></i>12:00 - 13:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-danger ps-3">
                                        <div class="text-muted small fw-bold uppercase">ISTIRAHAT & MAKAN SIANG</div>
                                        <?php if ($day['kuliner']): ?>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['kuliner']['slug'] ?>" class="text-decoration-none text-dark" target="_blank">
                                                <?= htmlspecialchars($day['kuliner']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-1"><?= htmlspecialchars($day['kuliner']['address'] ?? 'Pusat Kuliner Bogor') ?></p>
                                        <?php else: ?>
                                        <h5 class="fw-bold text-dark mb-1">Wisata Kuliner Khas Bogor</h5>
                                        <p class="text-muted small mb-1">Nikmati Soto Mie Bogor, Asinan Bogor, atau Resto lokal terdekat di sekitar lokasi wisata pagi.</p>
                                        <?php endif; ?>
                                        <span class="badge bg-light text-dark border">Estimasi Rp 35.000 - Rp 60.000 / porsi</span>
                                    </div>
                                </div>

                                <!-- Slot 3: Sore -->
                                <?php if ($day['sore']): ?>
                                <div class="d-flex gap-3 <?= $day['hotel'] ? 'mb-4' : '' ?> align-items-start">
                                    <div class="badge-time bg-info-subtle text-info fw-bold rounded-pill px-3 py-1 text-center" style="font-size:0.78rem; min-width:110px;">
                                        <i class="fas fa-cloud-sun me-1"></i>14:00 - 17:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-info ps-3">
                                        <div class="text-muted small fw-bold uppercase">WISATA SORE</div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['sore']['slug'] ?>" class="text-decoration-none text-dark" target="_blank">
                                                <?= htmlspecialchars($day['sore']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($day['sore']['address'] ?? 'Bogor') ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border"><?= formatRupiah($day['sore']['ticket_price_weekday'] ?? $day['sore']['ticket_price']) ?></span>
                                            <span class="badge bg-light text-dark border">⭐ <?= $day['sore']['avg_rating'] ?></span>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['sore']['latitude'] ?>,<?= $day['sore']['longitude'] ?>" target="_blank" class="text-primary small text-decoration-none fw-semibold">
                                                <i class="fas fa-directions me-1"></i>Petunjuk Arah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Slot 4: Hotel (jika bermalam) -->
                                <?php if ($day['hotel']): ?>
                                <div class="d-flex gap-3 align-items-start pt-2">
                                    <div class="badge-time bg-primary-subtle text-primary fw-bold rounded-pill px-3 py-1 text-center" style="font-size:0.78rem; min-width:110px;">
                                        <i class="fas fa-bed me-1"></i>MALAM
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-primary ps-3">
                                        <div class="text-muted small fw-bold uppercase">PENGINAPAN / REKOMENDASI HOTEL</div>
                                        <h5 class="fw-bold text-dark mb-1">
                                            <a href="<?= BASE_URL ?>/hotels/<?= $day['hotel']['id'] ?>" class="text-decoration-none text-dark" target="_blank">
                                                <?= htmlspecialchars($day['hotel']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-primary me-1"></i>Dekat <?= htmlspecialchars($day['hotel']['destination_name']) ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-primary text-white">Mulai Rp <?= number_format($day['hotel']['price_start'], 0, ',', '.') ?> / malam</span>
                                            <span class="badge bg-warning text-dark"><?= str_repeat('★', (int)$day['hotel']['star_rating']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: #fff !important; }
    .navbar, footer, .print-hide, .sticky-top { display: none !important; }
    #printableItinerary { width: 100% !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
}
</style>
