<?php
/**
 * app/views/itinerary/index.php
 * Smart Itinerary Planner View — SobatBogor
 */


?>

<!-- Header Banner -->
<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #1c1f2e 100%); padding: 3.5rem 0 2.5rem; position: relative; border-bottom: 3px solid #ea580c;">
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
        <div class="col-lg-4 form-sidebar">
            <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
                <div style="height:4px;background:linear-gradient(90deg,#ea580c,#f97316);"></div>
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size:1.05rem;color:#0f172a;">
                        <span style="width:32px;height:32px;background:rgba(234,88,12,.1);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;">
                            <i class="fas fa-sliders-h" style="color:#ea580c;font-size:.85rem;"></i>
                        </span>
                        Atur Rencana Liburan
                    </h5>
                    <p class="text-muted mb-0" style="font-size:.83rem;">Sesuaikan preferensi liburanmu di bawah ini</p>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/itinerary" method="GET" id="itineraryForm">
                        <input type="hidden" name="generate" value="1">

                        <!-- 1. Durasi Liburan -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2" style="font-size:.82rem;color:#374151;letter-spacing:.3px;">
                                <i class="fas fa-calendar-day me-1" style="color:#ea580c;"></i> 1. Durasi Liburan
                            </label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur1" value="1" <?= $duration == 1 ? 'checked' : '' ?>>
                                    <label class="btn btn-dur w-100 py-2 rounded-3 text-center" for="dur1">
                                        <i class="fas fa-sun d-block mb-1" style="font-size:1rem;"></i>1 Hari
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur2" value="2" <?= $duration == 2 ? 'checked' : '' ?>>
                                    <label class="btn btn-dur w-100 py-2 rounded-3 text-center" for="dur2">
                                        <i class="fas fa-moon d-block mb-1" style="font-size:1rem;"></i>2H 1M
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="duration" id="dur3" value="3" <?= $duration == 3 ? 'checked' : '' ?>>
                                    <label class="btn btn-dur w-100 py-2 rounded-3 text-center" for="dur3">
                                        <i class="fas fa-star d-block mb-1" style="font-size:1rem;"></i>3H 2M
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Budget Tier -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2" style="font-size:.82rem;color:#374151;letter-spacing:.3px;">
                                <i class="fas fa-wallet me-1" style="color:#16a34a;"></i> 2. Estimasi Budget
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
                            <label class="form-label fw-semibold mb-2" style="font-size:.82rem;color:#374151;letter-spacing:.3px;">
                                <i class="fas fa-map-signs me-1" style="color:#ea580c;"></i> 3. Preferensi Tempat <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($categories as $cat): ?>
                                <?php $isChecked = in_array($cat['slug'], $selectedCats); ?>
                                <input type="checkbox" class="btn-check" name="categories[]" id="cat-<?= $cat['id'] ?>" value="<?= htmlspecialchars($cat['slug']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <label class="cat-pill" for="cat-<?= $cat['id'] ?>">
                                    <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?>"></i>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="btnGenerate" class="btn w-100 fw-bold rounded-3" style="background:#ea580c;color:#fff;padding:0.75rem 1rem;font-size:0.95rem;border:none;transition:all .25s;">
                            <i class="fas fa-route me-2"></i>Buat Itinerary Otomatis
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
                <div class="budget-card card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <span class="badge mb-2" style="background:rgba(234,88,12,.2);color:#fb923c;border:1px solid rgba(251,146,60,.3);">Estimasi Total Biaya / Orang</span>
                                <h2 class="fw-bold mb-1 budget-total">
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
                    <div class="day-card card border-0 shadow-sm">
                        <div class="card-header d-flex align-items-center justify-content-between" style="border-bottom:none;">
                            <h5 class="fw-bold mb-0" style="font-size:1rem;"><i class="fas fa-calendar-day me-2" style="color:#fb923c;"></i>HARI KE-<?= $day['day_number'] ?></h5>
                            <span class="badge rounded-pill px-3 fw-semibold" style="background:rgba(255,255,255,.12);color:#e2e8f0;font-size:.72rem;">Agenda Harian</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline px-1">

                                <!-- Slot 1: Pagi -->
                                <?php if ($day['pagi']): ?>
                                <div class="timeline-slot">
                                    <div class="time-badge bg-warning-subtle text-warning">
                                        <i class="fas fa-sun me-1"></i>08:00 - 11:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-warning ps-3">
                                        <div class="text-muted small fw-bold mb-1" style="letter-spacing:.5px;font-size:.7rem;">WISATA PAGI</div>
                                        <h5 class="mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['pagi']['slug'] ?>" class="dest-link" target="_blank">
                                                <?= htmlspecialchars($day['pagi']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($day['pagi']['address'] ?? 'Bogor') ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border"><?= formatRupiah($day['pagi']['ticket_price_weekday'] ?? $day['pagi']['ticket_price']) ?></span>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="fas fa-star me-1" style="font-size:.7rem;"></i><?= $day['pagi']['avg_rating'] ?></span>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['pagi']['latitude'] ?>,<?= $day['pagi']['longitude'] ?>" target="_blank" class="text-primary small text-decoration-none fw-semibold">
                                                <i class="fas fa-directions me-1"></i>Petunjuk Arah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Slot 2: Makan Siang / Kuliner -->
                                <div class="timeline-slot">
                                    <div class="time-badge bg-danger-subtle text-danger">
                                        <i class="fas fa-utensils me-1"></i>12:00 - 13:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-danger ps-3">
                                        <div class="text-muted small fw-bold mb-1" style="letter-spacing:.5px;font-size:.7rem;">ISTIRAHAT &amp; MAKAN SIANG</div>
                                        <?php if ($day['kuliner']): ?>
                                        <h5 class="mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['kuliner']['slug'] ?>" class="dest-link" target="_blank">
                                                <?= htmlspecialchars($day['kuliner']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-1"><?= htmlspecialchars($day['kuliner']['address'] ?? 'Pusat Kuliner Bogor') ?></p>
                                        <?php else: ?>
                                        <h5 class="mb-1 fw-bold" style="color:#0f172a;">Wisata Kuliner Khas Bogor</h5>
                                        <p class="text-muted small mb-1">Nikmati Soto Mie Bogor, Asinan Bogor, atau Resto lokal terdekat di sekitar lokasi wisata pagi.</p>
                                        <?php endif; ?>
                                        <span class="badge" style="background:rgba(220,38,38,.08);color:#dc2626;border:1px solid rgba(220,38,38,.2);font-size:.75rem;">Estimasi Rp 35.000 - Rp 60.000 / porsi</span>
                                    </div>
                                </div>

                                <!-- Slot 3: Sore -->
                                <?php if ($day['sore']): ?>
                                <div class="timeline-slot">
                                    <div class="time-badge bg-info-subtle text-info">
                                        <i class="fas fa-cloud-sun me-1"></i>14:00 - 17:30
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-info ps-3">
                                        <div class="text-muted small fw-bold mb-1" style="letter-spacing:.5px;font-size:.7rem;">WISATA SORE</div>
                                        <h5 class="mb-1">
                                            <a href="<?= BASE_URL ?>/destinations/<?= $day['sore']['slug'] ?>" class="dest-link" target="_blank">
                                                <?= htmlspecialchars($day['sore']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($day['sore']['address'] ?? 'Bogor') ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border"><?= formatRupiah($day['sore']['ticket_price_weekday'] ?? $day['sore']['ticket_price']) ?></span>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="fas fa-star me-1" style="font-size:.7rem;"></i><?= $day['sore']['avg_rating'] ?></span>
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?= $day['sore']['latitude'] ?>,<?= $day['sore']['longitude'] ?>" target="_blank" class="text-primary small text-decoration-none fw-semibold">
                                                <i class="fas fa-directions me-1"></i>Petunjuk Arah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Slot 4: Hotel (jika bermalam) -->
                                <?php if ($day['hotel']): ?>
                                <div class="timeline-slot">
                                    <div class="time-badge" style="background:rgba(234,88,12,.1);color:#ea580c;">
                                        <i class="fas fa-bed me-1"></i>MALAM
                                    </div>
                                    <div class="flex-grow-1 border-start border-3 border-primary ps-3">
                                        <div class="text-muted small fw-bold mb-1" style="letter-spacing:.5px;font-size:.7rem;">PENGINAPAN / REKOMENDASI HOTEL</div>
                                        <h5 class="mb-1">
                                            <a href="<?= BASE_URL ?>/hotels/<?= $day['hotel']['id'] ?>" class="dest-link" target="_blank">
                                                <?= htmlspecialchars($day['hotel']['name']) ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-1" style="color:#ea580c;"></i>Dekat <?= htmlspecialchars($day['hotel']['destination_name']) ?></p>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge fw-semibold" style="background:rgba(234,88,12,.15);color:#ea580c;border:1px solid rgba(234,88,12,.3);">Mulai Rp <?= number_format($day['hotel']['price_start'], 0, ',', '.') ?> / malam</span>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><?= str_repeat('<i class="fas fa-star" style="font-size:.65rem;"></i>', (int)$day['hotel']['star_rating']) ?></span>
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
/* ── Itinerary Page Custom Styles ─────────────────────── */
:root {
    --orange: #ea580c;
    --orange-hover: #c2410c;
    --green: #16a34a;
    --dark: #0f172a;
    --gray-50: #f8fafc;
    --gray-200: #e2e8f0;
}

/* Duration buttons */
.btn-dur {
    font-size: 0.78rem;
    font-weight: 600;
    border: 2px solid var(--gray-200);
    color: #475569;
    background: #fff;
    transition: all .2s ease;
    line-height: 1.3;
}
.btn-check:checked + .btn-dur {
    background: var(--orange);
    border-color: var(--orange);
    color: #fff;
    box-shadow: 0 4px 12px rgba(234,88,12,.3);
}
.btn-dur:hover { border-color: var(--orange); color: var(--orange); }

/* Budget labels */
.style-budget-label {
    cursor: pointer;
    padding: 0.65rem 0.85rem !important;
    border: 2px solid var(--gray-200) !important;
    transition: all .2s ease;
    border-radius: 10px !important;
}
.style-budget-label:has(input:checked) {
    border-color: var(--orange) !important;
    background: rgba(234,88,12,.05) !important;
}
.style-budget-label:hover { border-color: #fb923c !important; }

/* Generate button */
#btnGenerate:hover {
    background: var(--orange-hover) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(234,88,12,.35);
}
#btnGenerate:active { transform: translateY(0); }

/* Day card */
.day-card {
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    overflow: hidden;
    background: #fff;
    transition: box-shadow .25s ease;
}
.day-card:hover { box-shadow: 0 8px 24px rgba(15,23,42,.1) !important; }
.day-card .card-header {
    background: var(--dark);
    color: #fff;
    border-left: 4px solid var(--orange);
    padding: 0.85rem 1.25rem;
}

/* Timeline slot */
.timeline-slot {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 10px;
    transition: background .2s ease;
    align-items: flex-start;
}
.timeline-slot:hover { background: var(--gray-50); }
.timeline-slot + .timeline-slot { border-top: 1px dashed var(--gray-200); }

/* Time badge */
.time-badge {
    min-width: 105px;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 20px;
    padding: 0.3rem 0.6rem;
    text-align: center;
    flex-shrink: 0;
    letter-spacing: .3px;
    margin-top: 2px;
}

/* Destination link */
.dest-link {
    color: var(--dark);
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    line-height: 1.3;
    transition: color .2s;
}
.dest-link:hover { color: var(--orange); }

/* Budget summary card */
.budget-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 16px;
    border-left: 4px solid var(--orange);
    color: #fff;
}
.budget-total {
    color: #fb923c;
    font-size: 2rem;
    font-weight: 800;
}

/* Empty state features */
.feature-box {
    border-radius: 12px;
    padding: 1rem 1.1rem;
    border: 1px solid var(--gray-200);
    background: var(--gray-50);
    height: 100%;
    transition: all .2s ease;
}
.feature-box:hover {
    border-color: var(--orange);
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(234,88,12,.1);
}
.feature-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* Category pills */
.cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.85rem;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
    user-select: none;
}
.cat-pill:hover {
    border-color: #ea580c;
    color: #ea580c;
    background: rgba(234,88,12,.05);
}
.btn-check:checked + .cat-pill {
    border-color: #ea580c;
    background: #ea580c;
    color: #fff;
    box-shadow: 0 3px 10px rgba(234,88,12,.3);
}

/* Form section divider */
.form-section-divider {
    border: none;
    border-top: 1px dashed #e2e8f0;
    margin: 1rem 0;
}

/* Timeline improved spacing */
.timeline-slot {
    padding: 1.1rem 0.75rem;
    margin-bottom: 0;
}
.timeline { padding: 0.25rem 0; }

/* Form sidebar sticky */
@media (min-width: 992px) {
    .form-sidebar { position: sticky; top: 80px; }
}

/* Print */
@media print {
    body { background: #fff !important; }
    .navbar, footer, .print-hide, .sticky-top { display: none !important; }
    #printableItinerary { width: 100% !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    .timeline-slot:hover { background: transparent; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Highlight active budget label on page load
    document.querySelectorAll('.style-budget-label input[type=radio]').forEach(function(r) {
        r.addEventListener('change', function() {
            document.querySelectorAll('.style-budget-label').forEach(l => l.classList.remove('active-budget'));
            this.closest('.style-budget-label').classList.add('active-budget');
        });
    });

    // Generate button loading state
    var form = document.getElementById('itineraryForm');
    var btn  = document.getElementById('btnGenerate');
    if (form && btn) {
        form.addEventListener('submit', function() {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyusun Rencana...';
            btn.disabled = true;
        });
    }
});
</script>
