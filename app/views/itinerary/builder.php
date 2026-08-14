<?php
/**
 * app/views/itinerary/builder.php
 * Interactive Drag-and-Drop Itinerary Builder
 */

// Helper format harga
function builderFormatPrice($price): string {
    if ($price === null || (float)$price == 0) return 'Gratis';
    return 'Rp ' . number_format((float)$price, 0, ',', '.');
}
?>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<style>
/* ── Builder CSS ────────────────────────────────────── */
.builder-hero {
    background: linear-gradient(135deg, #0a0f1e 0%, #1a2a4a 50%, #0d2316 100%);
    padding: 2.5rem 0 1.8rem;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.builder-hero::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(26,107,191,0.12) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.builder-title-input {
    background: rgba(255,255,255,0.1);
    border: 1.5px solid rgba(255,255,255,0.2);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 800;
    border-radius: 12px;
    padding: 0.4rem 0.9rem;
    width: 100%;
    max-width: 520px;
    transition: all 0.2s ease;
}
.builder-title-input:focus {
    background: rgba(255,255,255,0.18);
    border-color: #60a5fa;
    outline: none;
    box-shadow: 0 0 15px rgba(96,165,250,0.3);
}

.builder-layout {
    padding: 2rem 0 4rem;
    background: #f8fafc;
    min-height: calc(100vh - 250px);
}

/* Sidebar Destinasi */
.dest-sidebar {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 1.25rem;
    height: 780px;
    display: flex;
    flex-direction: column;
}
.dest-sidebar-list {
    overflow-y: auto;
    flex: 1;
    padding-right: 4px;
}
.dest-sidebar-list::-webkit-scrollbar {
    width: 5px;
}
.dest-sidebar-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

/* Destinasi Card Available */
.dest-item-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.75rem;
    margin-bottom: 0.65rem;
    cursor: grab;
    user-select: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.dest-item-card:hover {
    border-color: #1a6bbf;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(26,107,191,0.12);
}
.dest-item-card:active {
    cursor: grabbing;
}
.dest-item-card.sortable-ghost {
    opacity: 0.4;
    background: #eff6ff;
    border-style: dashed;
}

/* Main Days Container */
.day-column {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
    overflow: hidden;
}
.day-column-header {
    background: linear-gradient(135deg, #0d1529 0%, #0f2135 60%, #0a1e10 100%);
    color: #fff;
    padding: 0.9rem 1.35rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.day-dropzone {
    min-height: 110px;
    padding: 1rem;
    background: #fafcff;
    border: 2px dashed #cbd5e1;
    border-radius: 16px;
    margin: 1rem;
    transition: background 0.2s ease;
}
.day-dropzone.drag-active {
    background: #eff6ff;
    border-color: #1a6bbf;
}
.day-dropzone-empty {
    text-align: center;
    color: #94a3b8;
    padding: 1.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    pointer-events: none;
}

/* Item di dalam Day Dropzone */
.builder-item-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-left: 4px solid #1a6bbf;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    cursor: grab;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.builder-item-card:hover {
    border-color: #1a6bbf;
    box-shadow: 0 4px 14px rgba(26,107,191,0.12);
}

/* Cost Summary Floating Bar */
.cost-summary-card {
    background: #0a0f1e;
    color: #fff;
    border-radius: 20px;
    padding: 1.25rem 1.75rem;
    border: 1px solid rgba(26,107,191,0.3);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    margin-bottom: 1.5rem;
}

/* Print styling */
@media print {
    .navbar, footer, .dest-sidebar, .builder-actions-top, .btn-day-control, .btn-remove-item {
        display: none !important;
    }
    .builder-layout { padding: 0 !important; background: #fff !important; }
    .col-lg-8 { width: 100% !important; }
}
</style>

<!-- ══ HERO HEADER ══════════════════════════════════════ -->
<div class="builder-hero print-hide">
    <div class="container" style="position:relative;z-index:1;">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" style="color:rgba(255,255,255,0.5);font-size:0.82rem;text-decoration:none;">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/itinerary/builder" style="color:rgba(255,255,255,0.5);font-size:0.82rem;text-decoration:none;">Itinerary Saya</a></li>
                <li class="breadcrumb-item active" style="color:#fff;font-size:0.82rem;">Builder</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="font-size:0.7rem;font-weight:700;color:#4ade80;background:rgba(58,158,58,0.2);border:1px solid rgba(74,222,128,0.3);padding:0.2rem 0.75rem;border-radius:20px;letter-spacing:0.5px;text-transform:uppercase;">
                        <i class="fas fa-edit me-1"></i> Mode Interactive Drag &amp; Drop
                    </span>
                    <span id="saveStatusIndicator" style="font-size:0.75rem;color:rgba(255,255,255,0.65);font-weight:600;">
                        <i class="fas fa-check-circle text-success me-1"></i>Tersimpan
                    </span>
                </div>
                <input type="text" id="itineraryTitleInput" class="builder-title-input" value="<?= htmlspecialchars($itinerary['title']) ?>" placeholder="Nama Itinerary...">
            </div>

            <!-- Action buttons -->
            <div class="d-flex align-items-center gap-2 flex-wrap builder-actions-top">
                <button type="button" onclick="triggerManualSave()" class="btn btn-sm btn-light font-weight-bold px-3" style="border-radius:10px;font-weight:700;height:38px;">
                    <i class="fas fa-save me-1 text-primary"></i>Simpan
                </button>
                <a href="<?= BASE_URL ?>/peta?itinerary_id=<?= $itinerary['id'] ?>" class="btn btn-sm text-white font-weight-bold px-3" style="background:linear-gradient(135deg, #1a6bbf, #3a9e3a);border-radius:10px;font-weight:700;height:38px;display:inline-flex;align-items:center;">
                    <i class="fas fa-map-marked-alt me-1.5"></i>Lihat di Peta
                </a>
                <?php
                $waText = "Halo! Ini Itinerary Liburan Bogor saya (" . htmlspecialchars($itinerary['title']) . "): " . BASE_URL . "/peta?itinerary_id=" . $itinerary['id'];
                $waUrl  = "https://api.whatsapp.com/send?text=" . urlencode($waText);
                ?>
                <a href="<?= $waUrl ?>" target="_blank" class="btn btn-sm btn-success font-weight-bold px-3" style="border-radius:10px;font-weight:700;height:38px;display:inline-flex;align-items:center;">
                    <i class="fab fa-whatsapp me-1.5"></i>Bagikan
                </a>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-light font-weight-bold px-3" style="border-radius:10px;font-weight:700;height:38px;">
                    <i class="fas fa-print me-1"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══ BUILDER LAYOUT ═══════════════════════════════════ -->
<div class="builder-layout">
    <div class="container">
        <div class="row g-4">

            <!-- ══ LEFT SIDEBAR: DESTINASI TERSERDIA ══════════ -->
            <div class="col-lg-4 print-hide">
                <div class="dest-sidebar">
                    <div class="mb-3">
                        <h5 style="font-weight:800;color:#0f172a;font-size:1.05rem;margin-bottom:0.2rem;">
                            <i class="fas fa-compass me-2" style="color:#1a6bbf;"></i>Pilih Destinasi
                        </h5>
                        <p style="color:#64748b;font-size:0.78rem;margin:0;">Drag destinasi ke hari pilihanmu di sebelah kanan</p>
                    </div>

                    <!-- Search Box -->
                    <div class="position-relative mb-2.5">
                        <input type="text" id="destSearchInput" class="form-control form-control-sm" placeholder="Cari nama destinasi / alamat..." style="border-radius:30px;padding-left:2.2rem;border:1.5px solid #cbd5e1;font-size:0.82rem;">
                        <i class="fas fa-search position-absolute" style="left:0.85rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.78rem;"></i>
                    </div>

                    <!-- Category Pills -->
                    <div class="d-flex flex-wrap gap-1.5 mb-3" id="categoryFilterContainer">
                        <span class="badge cat-filter-chip active" data-slug="all" style="cursor:pointer;border-radius:20px;padding:0.35rem 0.75rem;font-size:0.72rem;background:#1a6bbf;color:#fff;">Semua</span>
                        <?php foreach ($categories as $cat): ?>
                        <span class="badge cat-filter-chip" data-slug="<?= htmlspecialchars($cat['slug']) ?>" style="cursor:pointer;border-radius:20px;padding:0.35rem 0.75rem;font-size:0.72rem;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;">
                            <?= htmlspecialchars($cat['name']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>

                    <!-- List Destinasi Draggable -->
                    <div class="dest-sidebar-list" id="sidebarDestList">
                        <?php foreach ($allDestinations as $d): ?>
                        <?php
                        $price = $d['ticket_price_weekday'] ?? $d['ticket_price'];
                        $img   = $d['primary_image'] ? BASE_URL . '/' . $d['primary_image'] : null;
                        ?>
                        <div class="dest-item-card"
                             data-id="<?= $d['id'] ?>"
                             data-name="<?= htmlspecialchars($d['name']) ?>"
                             data-category="<?= htmlspecialchars($d['category_name'] ?? 'Wisata') ?>"
                             data-slug="<?= htmlspecialchars($d['category_slug'] ?? '') ?>"
                             data-price="<?= (float)$price ?>"
                             data-address="<?= htmlspecialchars($d['address'] ?? 'Bogor') ?>">
                            <div style="width:42px;height:42px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#e2e8f0;">
                                <?php if ($img): ?>
                                <img src="<?= $img ?>" style="width:100%;height:100%;object-fit:cover;" alt="<?= htmlspecialchars($d['name']) ?>">
                                <?php else: ?>
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1a6bbf,#3a9e3a);color:#fff;font-size:0.8rem;">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div style="font-size:0.65rem;font-weight:700;color:#1a6bbf;text-transform:uppercase;letter-spacing:0.3px;">
                                    <?= htmlspecialchars($d['category_name'] ?? 'Wisata') ?>
                                </div>
                                <div style="font-size:0.85rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <?= htmlspecialchars($d['name']) ?>
                                </div>
                                <div style="font-size:0.73rem;color:#64748b;">
                                    <?= builderFormatPrice($price) ?>
                                </div>
                            </div>
                            <i class="fas fa-grip-vertical text-muted" style="font-size:0.8rem;"></i>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ══ RIGHT MAIN AREA: HARI & ESTIMASI BIAYA ════════ -->
            <div class="col-lg-8">

                <!-- Cost Summary Card -->
                <div class="cost-summary-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <div style="font-size:0.75rem;font-weight:700;color:#60a5fa;text-transform:uppercase;letter-spacing:0.5px;">
                            <i class="fas fa-calculator me-1"></i> Real-time Cost Estimation
                        </div>
                        <div style="font-size:0.75rem;color:rgba(255,255,255,0.6);">
                            <span id="statTotalItems">0</span> Destinasi · <span id="statTotalDays"><?= (int)($itinerary['max_day'] ?: 1) ?></span> Hari
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between flex-wrap gap-2">
                        <div>
                            <div style="font-size:1.8rem;font-weight:800;background:linear-gradient(135deg,#60a5fa,#4ade80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;" id="grandTotalDisplay">
                                Rp 0
                            </div>
                            <div style="font-size:0.75rem;color:rgba(255,255,255,0.5);">Estimasi Tiket + Makan per orang</div>
                        </div>
                        <div class="d-flex gap-3 text-end" style="font-size:0.8rem;">
                            <div>
                                <div style="color:rgba(255,255,255,0.5);">Tiket Wisata</div>
                                <div style="font-weight:700;color:#fff;" id="ticketTotalDisplay">Rp 0</div>
                            </div>
                            <div style="border-left:1px solid rgba(255,255,255,0.1);padding-left:0.75rem;">
                                <div style="color:rgba(255,255,255,0.5);">Est. Makan</div>
                                <div style="font-weight:700;color:#4ade80;" id="mealTotalDisplay">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Days Container -->
                <div id="daysContainer">
                    <?php
                    $daysData = $itinerary['days'] ?? [];
                    $maxDay   = max((int)($itinerary['max_day'] ?: 1), 1);
                    ?>

                    <?php for ($dayNum = 1; $dayNum <= $maxDay; $dayNum++): ?>
                    <?php $dayItems = $daysData[$dayNum] ?? []; ?>
                    <div class="day-column" data-day="<?= $dayNum ?>" id="day-column-<?= $dayNum ?>">
                        <div class="day-column-header">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:28px;height:28px;border-radius:8px;background:rgba(96,165,250,0.2);color:#60a5fa;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;">
                                    <?= $dayNum ?>
                                </div>
                                <div style="font-weight:800;font-size:0.95rem;">HARI KE-<?= $dayNum ?></div>
                            </div>
                            <span class="badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);font-size:0.72rem;font-weight:600;" id="day-count-badge-<?= $dayNum ?>">
                                <?= count($dayItems) ?> Destinasi
                            </span>
                        </div>

                        <div class="day-dropzone" data-day="<?= $dayNum ?>" id="day-dropzone-<?= $dayNum ?>">
                            <?php if (empty($dayItems)): ?>
                            <div class="day-dropzone-empty">
                                <i class="fas fa-hand-pointer mb-1 d-block" style="font-size:1.2rem;color:#cbd5e1;"></i>
                                Drag destinasi dari panel kiri dan lepaskan di sini
                            </div>
                            <?php else: ?>
                            <?php foreach ($dayItems as $item): ?>
                            <?php $price = $item['ticket_price_weekday'] ?? $item['ticket_price']; ?>
                            <div class="builder-item-card" data-id="<?= $item['destination_id'] ?>" data-price="<?= (float)$price ?>">
                                <div class="d-flex align-items-center gap-2.5 flex-grow-1" style="min-width:0;">
                                    <i class="fas fa-grip-vertical text-muted" style="cursor:grab;"></i>
                                    <div style="min-width:0;">
                                        <div style="font-size:0.65rem;font-weight:700;color:#1a6bbf;text-transform:uppercase;">
                                            <?= htmlspecialchars($item['category_name'] ?? 'Wisata') ?>
                                        </div>
                                        <div style="font-size:0.88rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </div>
                                        <div style="font-size:0.75rem;color:#64748b;">
                                            <?= builderFormatPrice($price) ?> · <i class="fas fa-map-marker-alt text-danger ms-1 me-0.5"></i><?= htmlspecialchars(($item['address'] ?? 'Bogor')) ?>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm text-danger p-1 btn-remove-item" onclick="removeBuilderItem(this)" title="Hapus dari Hari">
                                    <i class="fas fa-times-circle" style="font-size:1.1rem;"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Controls untuk Tambah / Kurang Hari -->
                <div class="d-flex gap-2 mb-4 print-hide">
                    <button type="button" id="btnAddDayBtn" class="btn flex-fill" style="background:#fff;border:1.5px dashed #1a6bbf;color:#1a6bbf;font-weight:700;border-radius:14px;padding:0.75rem;">
                        <i class="fas fa-plus-circle me-1.5"></i>Tambah Hari Baru
                    </button>
                    <button type="button" id="btnRemoveLastDayBtn" class="btn btn-outline-danger" style="font-weight:700;border-radius:14px;padding:0.75rem 1.25rem;">
                        <i class="fas fa-minus-circle me-1"></i>Hapus Hari Terakhir
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
const ITINERARY_ID = <?= (int) $itinerary['id'] ?>;
const BASE_URL_JS  = '<?= BASE_URL ?>';

let daySortables = [];
let sidebarSortable = null;
let autoSaveTimer = null;

document.addEventListener('DOMContentLoaded', function() {
    initSortables();
    initFilters();
    initTitleAutoSave();
    recalculateCosts();

    document.getElementById('btnAddDayBtn').addEventListener('click', addNewDay);
    document.getElementById('btnRemoveLastDayBtn').addEventListener('click', removeLastDay);
});

// ── Inisialisasi SortableJS ────────────────────────────
function initSortables() {
    const sidebarEl = document.getElementById('sidebarDestList');

    // Sidebar: clone item saat di-drag
    sidebarSortable = new Sortable(sidebarEl, {
        group: {
            name: 'itineraryGroup',
            pull: 'clone',
            put: false
        },
        sort: false,
        animation: 150,
        ghostClass: 'sortable-ghost'
    });

    // Inisialisasi dropzone di tiap hari
    document.querySelectorAll('.day-dropzone').forEach(dropzone => {
        setupDayDropzone(dropzone);
    });
}

function setupDayDropzone(dropzone) {
    const sortable = new Sortable(dropzone, {
        group: 'itineraryGroup',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onAdd: function(evt) {
            const itemEl = evt.item;
            const destId = itemEl.getAttribute('data-id');

            // Cek apakah item di-clone dari sidebar
            if (itemEl.classList.contains('dest-item-card')) {
                // Ubah struktur card menjadi builder-item-card
                const name     = itemEl.getAttribute('data-name');
                const cat      = itemEl.getAttribute('data-category');
                const price    = parseFloat(itemEl.getAttribute('data-price') || 0);
                const priceText= formatPriceJS(price);
                const address  = itemEl.getAttribute('data-address');

                const newCard = document.createElement('div');
                newCard.className = 'builder-item-card';
                newCard.setAttribute('data-id', destId);
                newCard.setAttribute('data-price', price);
                newCard.innerHTML = `
                    <div class="d-flex align-items-center gap-2.5 flex-grow-1" style="min-width:0;">
                        <i class="fas fa-grip-vertical text-muted" style="cursor:grab;"></i>
                        <div style="min-width:0;">
                            <div style="font-size:0.65rem;font-weight:700;color:#1a6bbf;text-transform:uppercase;">${cat}</div>
                            <div style="font-size:0.88rem;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</div>
                            <div style="font-size:0.75rem;color:#64748b;">${priceText} · <i class="fas fa-map-marker-alt text-danger ms-1 me-0.5"></i>${address}</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm text-danger p-1 btn-remove-item" onclick="removeBuilderItem(this)" title="Hapus dari Hari">
                        <i class="fas fa-times-circle" style="font-size:1.1rem;"></i>
                    </button>
                `;
                itemEl.parentNode.replaceChild(newCard, itemEl);
            }

            cleanDropzoneEmptyState(dropzone);
            updateDayBadges();
            recalculateCosts();
            triggerAutoSave();
        },
        onUpdate: function() {
            triggerAutoSave();
        },
        onRemove: function(evt) {
            cleanDropzoneEmptyState(evt.from);
            updateDayBadges();
            recalculateCosts();
            triggerAutoSave();
        }
    });
    daySortables.push(sortable);
}

function cleanDropzoneEmptyState(dropzone) {
    const emptyMsg = dropzone.querySelector('.day-dropzone-empty');
    const items    = dropzone.querySelectorAll('.builder-item-card');

    if (items.length > 0 && emptyMsg) {
        emptyMsg.remove();
    } else if (items.length === 0 && !emptyMsg) {
        dropzone.innerHTML = `
            <div class="day-dropzone-empty">
                <i class="fas fa-hand-pointer mb-1 d-block" style="font-size:1.2rem;color:#cbd5e1;"></i>
                Drag destinasi dari panel kiri dan lepaskan di sini
            </div>
        `;
    }
}

// ── Hapus Item dari Hari ──────────────────────────────
function removeBuilderItem(btn) {
    const card = btn.closest('.builder-item-card');
    const dropzone = card.closest('.day-dropzone');

    card.style.transition = 'all 0.25s ease';
    card.style.opacity = '0';
    card.style.transform = 'scale(0.8)';

    setTimeout(() => {
        card.remove();
        cleanDropzoneEmptyState(dropzone);
        updateDayBadges();
        recalculateCosts();
        triggerAutoSave();
    }, 250);
}

// ── Tambah & Hapus Hari ───────────────────────────────
function addNewDay() {
    const container = document.getElementById('daysContainer');
    const existingDays = container.querySelectorAll('.day-column');
    const newDayNum = existingDays.length + 1;

    const dayDiv = document.createElement('div');
    dayDiv.className = 'day-column';
    dayDiv.setAttribute('data-day', newDayNum);
    dayDiv.id = 'day-column-' + newDayNum;
    dayDiv.innerHTML = `
        <div class="day-column-header">
            <div class="d-flex align-items-center gap-2">
                <div style="width:28px;height:28px;border-radius:8px;background:rgba(96,165,250,0.2);color:#60a5fa;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.85rem;">
                    ${newDayNum}
                </div>
                <div style="font-weight:800;font-size:0.95rem;">HARI KE-${newDayNum}</div>
            </div>
            <span class="badge" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);font-size:0.72rem;font-weight:600;" id="day-count-badge-${newDayNum}">
                0 Destinasi
            </span>
        </div>
        <div class="day-dropzone" data-day="${newDayNum}" id="day-dropzone-${newDayNum}">
            <div class="day-dropzone-empty">
                <i class="fas fa-hand-pointer mb-1 d-block" style="font-size:1.2rem;color:#cbd5e1;"></i>
                Drag destinasi dari panel kiri dan lepaskan di sini
            </div>
        </div>
    `;

    container.appendChild(dayDiv);
    setupDayDropzone(dayDiv.querySelector('.day-dropzone'));
    updateDayBadges();
    recalculateCosts();
    triggerAutoSave();
}

function removeLastDay() {
    const container = document.getElementById('daysContainer');
    const existingDays = container.querySelectorAll('.day-column');
    if (existingDays.length <= 1) {
        Swal.fire({ toast:true, position:'top-end', icon:'info', title:'Itinerary minimal memiliki 1 hari', showConfirmButton:false, timer:2000 });
        return;
    }

    const lastDay = existingDays[existingDays.length - 1];
    const itemsInLastDay = lastDay.querySelectorAll('.builder-item-card').length;

    if (itemsInLastDay > 0) {
        Swal.fire({
            title: 'Hapus Hari Terakhir?',
            text: `Hari ke-${existingDays.length} memiliki ${itemsInLastDay} destinasi yang akan ikut terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(r => {
            if (r.isConfirmed) {
                lastDay.remove();
                updateDayBadges();
                recalculateCosts();
                triggerAutoSave();
            }
        });
    } else {
        lastDay.remove();
        updateDayBadges();
        recalculateCosts();
        triggerAutoSave();
    }
}

function updateDayBadges() {
    const days = document.querySelectorAll('.day-column');
    document.getElementById('statTotalDays').textContent = days.length;

    days.forEach((dayEl, idx) => {
        const count = dayEl.querySelectorAll('.builder-item-card').length;
        const badge = dayEl.querySelector('[id^="day-count-badge-"]');
        if (badge) badge.textContent = `${count} Destinasi`;
    });
}

// ── Real-time Cost Estimation ─────────────────────────
function recalculateCosts() {
    let ticketTotal = 0;
    let totalItems  = 0;
    const daysCount = document.querySelectorAll('.day-column').length;

    document.querySelectorAll('.day-dropzone .builder-item-card').forEach(card => {
        ticketTotal += parseFloat(card.getAttribute('data-price') || 0);
        totalItems++;
    });

    // Estimasi makan Rp 75.000 / hari
    const mealEstPerDay = 75000;
    const mealTotal     = daysCount * mealEstPerDay;
    const grandTotal    = ticketTotal + mealTotal;

    document.getElementById('statTotalItems').textContent    = totalItems;
    document.getElementById('ticketTotalDisplay').textContent = formatPriceJS(ticketTotal);
    document.getElementById('mealTotalDisplay').textContent   = formatPriceJS(mealTotal);
    document.getElementById('grandTotalDisplay').textContent  = formatPriceJS(grandTotal);
}

function formatPriceJS(val) {
    if (!val || val === 0) return 'Gratis';
    return 'Rp ' + Math.round(val).toLocaleString('id-ID');
}

// ── Sidebar Filters ───────────────────────────────────
function initFilters() {
    const searchInput = document.getElementById('destSearchInput');
    const chips       = document.querySelectorAll('.cat-filter-chip');

    let activeCat = 'all';

    function applyFilter() {
        const query = searchInput.value.toLowerCase().trim();

        document.querySelectorAll('#sidebarDestList .dest-item-card').forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const addr = card.getAttribute('data-address').toLowerCase();
            const cat  = card.getAttribute('data-slug');

            const matchQuery = !query || name.includes(query) || addr.includes(query);
            const matchCat   = activeCat === 'all' || cat === activeCat;

            if (matchQuery && matchCat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', applyFilter);

    chips.forEach(chip => {
        chip.addEventListener('click', function() {
            chips.forEach(c => {
                c.classList.remove('active');
                c.style.background = '#f1f5f9';
                c.style.color = '#475569';
            });
            this.classList.add('active');
            this.style.background = '#1a6bbf';
            this.style.color = '#fff';
            activeCat = this.getAttribute('data-slug');
            applyFilter();
        });
    });
}

// ── Auto Save System ──────────────────────────────────
function initTitleAutoSave() {
    const input = document.getElementById('itineraryTitleInput');
    input.addEventListener('input', function() {
        triggerAutoSave();
    });
}

function triggerAutoSave() {
    setSaveStatus('saving');
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        executeSave();
    }, 700);
}

function triggerManualSave() {
    clearTimeout(autoSaveTimer);
    executeSave();
}

function executeSave() {
    const title = document.getElementById('itineraryTitleInput').value.trim();

    // Kumpulkan seluruh item per hari
    const items = [];
    document.querySelectorAll('.day-column').forEach(dayEl => {
        const dayNum = parseInt(dayEl.getAttribute('data-day'), 10);
        let sortOrder = 0;

        dayEl.querySelectorAll('.day-dropzone .builder-item-card').forEach(card => {
            const destId = parseInt(card.getAttribute('data-id'), 10);
            if (destId > 0) {
                items.push({
                    destination_id: destId,
                    day_number: dayNum,
                    sort_order: sortOrder++
                });
            }
        });
    });

    fetch(`${BASE_URL_JS}/itinerary/builder/${ITINERARY_ID}/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            title: title,
            items: items
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            setSaveStatus('saved', data.updated_at);
        } else {
            setSaveStatus('error');
        }
    })
    .catch(() => {
        setSaveStatus('error');
    });
}

function setSaveStatus(status, timeStr = '') {
    const el = document.getElementById('saveStatusIndicator');
    if (!el) return;

    if (status === 'saving') {
        el.innerHTML = `<i class="fas fa-spinner fa-spin text-warning me-1"></i>Menyimpan...`;
    } else if (status === 'saved') {
        el.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i>Tersimpan ${timeStr}`;
    } else {
        el.innerHTML = `<i class="fas fa-exclamation-triangle text-danger me-1"></i>Gagal Menyimpan`;
    }
}
</script>
