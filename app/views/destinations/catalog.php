<?php
function formatPrice(string|int|null $price): string {
    if ($price === null || $price == 0) return '<span class="text-success fw-bold">Gratis</span>';
    return 'Rp ' . number_format((float)$price, 0, ',', '.');
}
function renderStars(float $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= round($rating)
            ? '<i class="fas fa-star" style="color:#f59e0b;font-size:0.72rem;"></i>'
            : '<i class="far fa-star" style="color:#d1d5db;font-size:0.72rem;"></i>';
    }
    return $html;
}
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
#btnViewGrid.active, #btnViewMap.active {
    background: var(--gradient) !important;
    color: var(--white) !important;
    border-color: transparent !important;
}
#btnViewGrid:not(.active), #btnViewMap:not(.active) {
    background: var(--white) !important;
    color: var(--primary) !important;
    border-color: var(--primary) !important;
}
#btnViewGrid:hover:not(.active), #btnViewMap:hover:not(.active) {
    background: var(--primary-light) !important;
}
.map-popup-card {
    font-family: 'Outfit', sans-serif;
    min-width: 180px;
}
.map-popup-card img {
    width: 100%;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 6px;
}
.map-popup-card h5 {
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 2px;
    color: var(--dark);
}
.map-popup-card p {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-bottom: 4px;
    line-height: 1.2;
}
.map-popup-card .price {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 6px;
}
.map-popup-btn {
    display: block;
    text-align: center;
    background: var(--gradient);
    color: #fff !important;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 6px;
    text-decoration: none;
    transition: var(--transition);
}
.map-popup-btn:hover {
    background: var(--gradient-hover);
    transform: translateY(-1px);
}
</style>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#0f172a,#1e293b);padding:3.5rem 0 2.5rem;">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/" style="color:rgba(255,255,255,0.6);">Beranda</a></li>
                <li class="breadcrumb-item active" style="color:rgba(255,255,255,0.9);">Katalog Wisata</li>
            </ol>
        </nav>
        <h1 style="color:#fff;font-weight:800;font-size:2rem;margin-bottom:0.5rem;">
            <i class="fas fa-map-marked-alt me-2" style="color:var(--primary);"></i>
            Katalog Wisata Bogor
        </h1>
        <p style="color:rgba(255,255,255,0.65);font-size:0.95rem;">
            <?= count($destinations) ?> destinasi ditemukan
            <?= $search ? ' untuk "<strong style="color:#fb923c;">' . htmlspecialchars($search) . '</strong>"' : '' ?>
            <?= $catSlug ? ' pada kategori <strong style="color:#fb923c;">' . htmlspecialchars($catSlug) . '</strong>' : '' ?>
        </p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">

        <!-- ── SIDEBAR FILTER ─────────────────────────────────── -->
        <div class="col-lg-3">
            <div class="filter-card" data-aos="fade-right">

                <!-- Search -->
                <div class="filter-title"><i class="fas fa-search me-2 text-primary-custom"></i>Cari Destinasi</div>
                <form action="<?= BASE_URL ?>/destinations" method="GET" class="mb-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" class="form-control" placeholder="Nama, lokasi..."
                               value="<?= htmlspecialchars($search) ?>" style="border-radius:8px 0 0 8px;font-family:'Outfit',sans-serif;">
                        <?php if ($catSlug): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($catSlug) ?>">
                        <?php endif; ?>
                        <button class="btn btn-sm" type="submit"
                                style="background:var(--primary);color:#fff;border-radius:0 8px 8px 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Kategori -->
                <div class="filter-title"><i class="fas fa-th-large me-2 text-primary-custom"></i>Kategori</div>
                <div class="d-flex flex-column gap-1 mb-4">
                    <a href="<?= BASE_URL ?>/destinations<?= $search ? '?q=' . urlencode($search) : '' ?>"
                       class="d-flex justify-content-between align-items-center px-3 py-2 rounded-3 text-decoration-none <?= !$catSlug ? 'active' : '' ?>"
                       style="<?= !$catSlug ? 'background:var(--primary);color:#fff;' : 'color:var(--gray-700);' ?> font-size:0.88rem;transition:var(--transition);">
                        <span><i class="fas fa-th-large me-2"></i>Semua Kategori</span>
                        <span class="badge bg-secondary rounded-pill" style="font-size:0.7rem;"><?= array_sum(array_column($categories, 'total_destinations')) ?></span>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="<?= BASE_URL ?>/destinations?category=<?= htmlspecialchars($cat['slug']) ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
                       class="d-flex justify-content-between align-items-center px-3 py-2 rounded-3 text-decoration-none <?= $catSlug === $cat['slug'] ? 'active' : '' ?>"
                       style="<?= $catSlug === $cat['slug'] ? 'background:var(--primary);color:#fff;' : 'color:var(--gray-700);' ?> font-size:0.88rem;transition:var(--transition);"
                       onmouseover="if(!this.classList.contains('active')){this.style.background='var(--gray-100)';}"
                       onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';}">
                        <span>
                            <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?> me-2"></i>
                            <?= htmlspecialchars($cat['name']) ?>
                        </span>
                        <span class="badge rounded-pill" style="background:rgba(0,0,0,0.1);font-size:0.7rem;"><?= $cat['total_destinations'] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Urutkan -->
                <div class="filter-title"><i class="fas fa-sort me-2 text-primary-custom"></i>Urutkan</div>
                <form action="<?= BASE_URL ?>/destinations" method="GET">
                    <?php if ($search):  ?><input type="hidden" name="q"        value="<?= htmlspecialchars($search)  ?>"><?php endif; ?>
                    <?php if ($catSlug): ?><input type="hidden" name="category" value="<?= htmlspecialchars($catSlug) ?>"><?php endif; ?>
                    <select name="sort" class="form-select form-select-sm mb-3"
                            onchange="this.form.submit()"
                            style="font-family:'Outfit',sans-serif;border-radius:8px;font-size:0.88rem;">
                        <option value="terbaru"    <?= $sort === 'terbaru'    ? 'selected' : '' ?>>Terbaru</option>
                        <option value="rating"     <?= $sort === 'rating'     ? 'selected' : '' ?>>Rating Tertinggi</option>
                        <option value="nama"       <?= $sort === 'nama'       ? 'selected' : '' ?>>Nama A–Z</option>
                        <option value="harga_asc"  <?= $sort === 'harga_asc'  ? 'selected' : '' ?>>Harga Termurah</option>
                        <option value="harga_desc" <?= $sort === 'harga_desc' ? 'selected' : '' ?>>Harga Termahal</option>
                    </select>
                </form>

                <!-- Reset -->
                <a href="<?= BASE_URL ?>/destinations" class="btn btn-sm w-100"
                   style="border:1.5px solid var(--gray-200);border-radius:8px;font-size:0.85rem;color:var(--gray-700);font-family:'Outfit',sans-serif;">
                    <i class="fas fa-redo me-1"></i>Reset Filter
                </a>
            </div>
        </div>

        <!-- ── GRID DESTINASI ──────────────────────────────────── -->
        <div class="col-lg-9">

            <?php if (empty($destinations)): ?>
            <!-- Empty state -->
            <div class="text-center py-5" data-aos="fade-up">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--gray-100);margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-map-pin" style="font-size:2rem;color:var(--gray-500);"></i>
                </div>
                <h4 style="color:var(--dark);font-weight:700;">Destinasi Tidak Ditemukan</h4>
                <p style="color:var(--gray-500);">Coba ubah kata kunci atau hapus filter yang aktif.</p>
                <a href="<?= BASE_URL ?>/destinations" class="btn-primary-custom btn mt-2">Reset Pencarian</a>
            </div>

            <?php else: ?>
            <!-- View Toggle & Info -->
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up" style="background:#fff; padding: 0.75rem 1.25rem; border-radius:12px; border:1px solid var(--gray-200);">
                <span style="color:var(--gray-500); font-size:0.9rem;">
                    Menampilkan <strong style="color:var(--primary);"><?= count($destinations) ?></strong> destinasi terbaik
                </span>
                <div class="btn-group btn-group-sm" role="group" style="box-shadow:var(--shadow-sm); border-radius:30px; overflow:hidden;">
                    <button type="button" class="btn px-3 py-1.5 active" id="btnViewGrid" 
                            style="font-family:'Outfit',sans-serif; font-weight:600; font-size:0.82rem; transition:var(--transition); border:1px solid var(--primary);">
                        <i class="fas fa-th-large me-1"></i>Grid
                    </button>
                    <button type="button" class="btn px-3 py-1.5" id="btnViewMap" 
                            style="font-family:'Outfit',sans-serif; font-weight:600; font-size:0.82rem; transition:var(--transition); border:1px solid var(--primary); border-left:none;">
                        <i class="fas fa-map-marked-alt me-1"></i>Peta
                    </button>
                </div>
            </div>

            <!-- Peta Container -->
            <div id="catalogMap" style="display: none; height: 500px; border-radius: 12px; border: 1px solid var(--gray-200); z-index: 1; margin-bottom: 2rem; box-shadow: var(--shadow-sm);"></div>

            <!-- Grid Container -->
            <div id="catalogGrid" class="row g-4">
                <?php foreach ($destinations as $idx => $dest): ?>
                <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?= ($idx % 3) * 80 ?>">
                    <div class="destination-card h-100">
                        <div class="card-img-wrapper">
                            <?php if ($dest['primary_image']): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($dest['primary_image']) ?>"
                                 alt="<?= htmlspecialchars($dest['name']) ?>" loading="lazy">
                            <?php else: ?>
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#fed7aa,#fb923c);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-mountain" style="font-size:2.5rem;color:rgba(255,255,255,0.5);"></i>
                            </div>
                            <?php endif; ?>
                            <span class="badge-category"><?= htmlspecialchars($dest['category_name'] ?? 'Wisata') ?></span>
                            <?php $isWishlisted = in_array($dest['id'], $wishlistIds); ?>
                            <button class="wishlist-btn <?= $isWishlisted ? 'active' : '' ?>"
                                    id="wbtn-<?= $dest['id'] ?>"
                                    onclick="toggleWishlist(<?= $dest['id'] ?>, this)"
                                    title="Wishlist">
                                <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart"></i>
                            </button>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title">
                                <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($dest['slug']) ?>">
                                    <?= htmlspecialchars($dest['name']) ?>
                                </a>
                            </h3>
                            <p class="card-address">
                                <i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>
                                <?= htmlspecialchars($dest['address'] ?? 'Bogor') ?>
                            </p>
                            <?php $status = getDestinationStatus($dest['open_hours'] ?? ''); ?>
                            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                <span class="badge" style="<?= $status['style'] ?>; font-size:0.7rem; padding:0.22rem 0.5rem; border-radius:30px; font-weight:600;" title="<?= htmlspecialchars($status['detail']) ?>">
                                    <?= $status['label'] ?>
                                </span>
                                <?php if ($status['status'] === 'tutup'): ?>
                                    <span class="text-danger small fw-semibold" style="font-size:0.72rem;">
                                        <?= htmlspecialchars($status['detail']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer-info mt-auto">
                                <?php $pricing = getDestinationPricing($dest); ?>
                                <div>
                                    <div class="ticket-price"><?= $pricing['formatted_today'] ?> <span style="font-size:0.7rem;font-weight:normal;color:var(--gray-500);">/ orang</span></div>
                                    <div style="font-size:0.7rem;color:var(--gray-500);">
                                        <span>WD: <?= $pricing['formatted_weekday'] ?></span> | 
                                        <span>WE: <?= $pricing['formatted_weekend'] ?></span>
                                    </div>
                                </div>
                                <div class="rating-stars">
                                    <?= renderStars((float)$dest['avg_rating']) ?>
                                    <span style="color:var(--gray-500);font-size:0.76rem;margin-left:4px;">
                                        (<?= $dest['review_count'] ?>)
                                    </span>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($dest['slug']) ?>"
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
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
function toggleWishlist(destinationId, btn) {
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
            icon.classList.toggle('fas'); icon.classList.toggle('far');
            Swal.fire({ toast:true, position:'top-end', icon:'success',
                        title: isActive ? 'Dihapus dari Wishlist' : 'Ditambahkan ke Wishlist!',
                        showConfirmButton:false, timer:1800, timerProgressBar:true });
        }
    });
}

// ── PETA WISATA CATALOG LOGIC ──
let map = null;
let markers = [];
const destinationsData = <?= json_encode($destinations) ?>;
const baseUrl = '<?= BASE_URL ?>';

const btnViewGrid = document.getElementById('btnViewGrid');
const btnViewMap = document.getElementById('btnViewMap');
const catalogGrid = document.getElementById('catalogGrid');
const catalogMap = document.getElementById('catalogMap');

if (btnViewGrid && btnViewMap) {
    btnViewGrid.addEventListener('click', function() {
        btnViewGrid.classList.add('active');
        btnViewMap.classList.remove('active');
        if (catalogGrid) catalogGrid.style.display = 'flex';
        if (catalogMap) catalogMap.style.display = 'none';
    });

    btnViewMap.addEventListener('click', function() {
        btnViewMap.classList.add('active');
        btnViewGrid.classList.remove('active');
        if (catalogGrid) catalogGrid.style.display = 'none';
        if (catalogMap) catalogMap.style.display = 'block';
        
        initializeCatalogMap();
    });
}

function initializeCatalogMap() {
    if (map) {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
        return;
    }

    let defaultCenter = [-6.5981, 106.7994]; // Default: Kebun Raya Bogor
    let validCoords = [];

    destinationsData.forEach(dest => {
        if (dest.latitude && dest.longitude) {
            validCoords.push([parseFloat(dest.latitude), parseFloat(dest.longitude)]);
        }
    });

    if (validCoords.length > 0) {
        defaultCenter = validCoords[0];
    }

    map = L.map('catalogMap').setView(defaultCenter, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const markerBounds = [];

    destinationsData.forEach(dest => {
        if (dest.latitude && dest.longitude) {
            const lat = parseFloat(dest.latitude);
            const lng = parseFloat(dest.longitude);
            
            const marker = L.marker([lat, lng]).addTo(map);
            
            // Format Harga
            let priceHtml = '';
            if (!dest.ticket_price || parseFloat(dest.ticket_price) === 0) {
                priceHtml = '<div class="price">Gratis</div>';
            } else {
                const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(dest.ticket_price);
                priceHtml = '<div class="price">' + formattedPrice + '</div>';
            }

            // Image HTML
            let imgHtml = '';
            if (dest.primary_image) {
                imgHtml = '<img src="' + baseUrl + '/' + dest.primary_image + '" alt="' + dest.name + '">';
            } else {
                imgHtml = '<div style="width:100%; height:90px; background:linear-gradient(135deg,#fed7aa,#fb923c); border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:6px;"><i class="fas fa-mountain" style="color:rgba(255,255,255,0.6); font-size:1.5rem;"></i></div>';
            }

            const popupContent = `
                <div class="map-popup-card">
                    ${imgHtml}
                    <h5>${dest.name}</h5>
                    <p><i class="fas fa-map-marker-alt text-primary-custom me-1"></i>${dest.address ? dest.address.split(',')[0] : 'Bogor'}</p>
                    ${priceHtml}
                    <a href="${baseUrl}/destinations/${dest.slug}" class="map-popup-btn">Lihat Detail</a>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            markers.push(marker);
            markerBounds.push([lat, lng]);
        }
    });

    if (markerBounds.length > 0) {
        map.fitBounds(markerBounds, { padding: [40, 40] });
    }
}
</script>


