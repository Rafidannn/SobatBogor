<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<style>
#btnViewGrid.active, #btnViewMap.active {
    background: linear-gradient(135deg, #1a6bbf, #3a9e3a) !important;
    color: #ffffff !important;
    border-color: transparent !important;
}
#btnViewGrid:not(.active), #btnViewMap:not(.active) {
    background: #ffffff !important;
    color: var(--primary) !important;
    border-color: var(--primary) !important;
}
.hotel-card {
    background: #ffffff;
    border: 1px solid var(--gray-200);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.hotel-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(26, 107, 191, 0.12);
    border-color: var(--primary);
}
.hotel-img-wrapper {
    position: relative;
    height: 190px;
    overflow: hidden;
    background: #f1f5f9;
}
.hotel-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.hotel-card:hover .hotel-img-wrapper img {
    transform: scale(1.05);
}
.map-popup-card {
    font-family: 'Outfit', sans-serif;
    min-width: 200px;
}
.map-popup-card img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 8px;
}
</style>

<!-- Hero Header -->
<div style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 3.5rem 0 2.5rem; position: relative;">
    <div class="container text-center text-md-start">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge px-3 py-2 rounded-pill mb-2" style="background:rgba(58,158,58,0.2);color:#34d399;font-weight:600;font-size:0.8rem;border:1px solid rgba(52,211,153,0.3);">
                    <i class="fas fa-hotel me-1"></i> Rekomendasi Tempat Menginap
                </span>
                <h1 style="color: #fff; font-weight: 800; font-size: 2.2rem; margin-bottom: 0.5rem;">
                    Penginapan & Hotel Terdekat di Bogor
                </h1>
                <p style="color: rgba(255,255,255,0.75); font-size: 1rem; margin-bottom: 0;">
                    Temukan hotel nyaman dekat tempat wisata favoritmu di Puncak & Kota Bogor.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="btn-group p-1 bg-white rounded-pill shadow-sm" role="group">
                    <button type="button" class="btn btn-sm rounded-pill px-3 py-2 active" id="btnViewGrid" onclick="switchView('grid')">
                        <i class="fas fa-th-large me-1"></i> Kartu
                    </button>
                    <button type="button" class="btn btn-sm rounded-pill px-3 py-2" id="btnViewMap" onclick="switchView('map')">
                        <i class="fas fa-map-marked-alt me-1"></i> Peta Lokasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4 py-md-5">
    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #fff;">
        <div class="card-body p-3 p-md-4">
            <form action="<?= BASE_URL ?>/hotels" method="GET" class="row g-3 align-items-end">
                <!-- Search Keyword -->
                <div class="col-md-3">
                    <label class="form-label fw-600 small text-muted">Cari Nama Hotel / Lokasi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 bg-light"
                               placeholder="Nama hotel..." value="<?= htmlspecialchars($search) ?>" style="font-size:0.9rem;">
                    </div>
                </div>

                <!-- Star Rating -->
                <div class="col-md-2">
                    <label class="form-label fw-600 small text-muted">Rating Bintang</label>
                    <select name="star" class="form-select bg-light" style="font-size:0.9rem;">
                        <option value="0">Semua Bintang</option>
                        <?php for ($s = 5; $s >= 1; $s--): ?>
                        <option value="<?= $s ?>" <?= $star === $s ? 'selected' : '' ?>><?= $s ?> Bintang (⭐)</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Max Price -->
                <div class="col-md-2">
                    <label class="form-label fw-600 small text-muted">Maksimal Harga</label>
                    <select name="max_price" class="form-select bg-light" style="font-size:0.9rem;">
                        <option value="0">Semua Harga</option>
                        <option value="500000" <?= $maxPrice == 500000 ? 'selected' : '' ?>>S.d. Rp 500rb</option>
                        <option value="800000" <?= $maxPrice == 800000 ? 'selected' : '' ?>>S.d. Rp 800rb</option>
                        <option value="1200000" <?= $maxPrice == 1200000 ? 'selected' : '' ?>>S.d. Rp 1.2jt</option>
                        <option value="2000000" <?= $maxPrice == 2000000 ? 'selected' : '' ?>>S.d. Rp 2jt</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-600" style="font-size:0.9rem;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>

                <!-- Geolocation Button -->
                <div class="col-md-3">
                    <button type="button" id="btnGeoSort" class="btn btn-outline-primary w-100 rounded-3 py-2 fw-600" style="font-size:0.9rem; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                        <i class="fas fa-location-crosshairs"></i> Cari Hotel Terdekat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- VIEW 1: Grid Card View -->
    <div id="gridView">
        <?php if (empty($hotels)): ?>
        <div class="text-center py-5" data-aos="fade-up">
            <div style="width:90px;height:90px;border-radius:50%;background:var(--gray-100);margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-hotel fa-2x text-muted opacity-50"></i>
            </div>
            <h3 class="fw-bold text-dark h5 mb-2">Hotel Tidak Ditemukan</h3>
            <p class="text-muted small">Coba sesuaikan kata kunci atau filter batas harga kamu.</p>
            <a href="<?= BASE_URL ?>/hotels" class="btn btn-sm btn-outline-primary rounded-pill px-4 mt-2">Reset Filter</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($hotels as $h): ?>
            <div class="col-sm-6 col-lg-4 col-xl-3 hotel-grid-item" 
                 data-lat="<?= (float)$h['latitude'] ?>" 
                 data-lng="<?= (float)$h['longitude'] ?>"
                 data-aos="fade-up">
                <div class="hotel-card">
                    <!-- Image -->
                    <div class="hotel-img-wrapper">
                        <?php if (!empty($h['image_path'])): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($h['image_path']) ?>" alt="<?= htmlspecialchars($h['name']) ?>">
                        <?php else: ?>
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-hotel fa-2x text-muted opacity-25"></i>
                        </div>
                        <?php endif; ?>

                        <!-- Bintang Badge -->
                        <div style="position:absolute;top:10px;left:10px;background:rgba(15,23,42,0.85);backdrop-filter:blur(4px);padding:3px 10px;border-radius:20px;color:#f59e0b;font-size:0.75rem;font-weight:700;">
                            <?php for ($s = 1; $s <= $h['star_rating']; $s++): ?>★<?php endfor; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <span class="badge bg-primary-subtle text-primary mb-1 align-self-start" style="font-size:0.68rem;font-weight:600;">
                            Dekat <?= htmlspecialchars($h['destination_name']) ?>
                        </span>
                        <h3 class="h6 fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($h['name']) ?>">
                            <a href="<?= BASE_URL ?>/hotels/<?= $h['id'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($h['name']) ?>
                            </a>
                        </h3>
                        <p class="text-muted small mb-1 text-truncate" style="font-size:0.75rem;">
                            <?= htmlspecialchars($h['address'] ?? 'Bogor, Jawa Barat') ?>
                        </p>
                        <!-- Distance Badge -->
                        <div class="geo-distance-badge mb-2 d-none" style="font-size: 0.72rem; font-weight: 600; color: var(--secondary);">
                            <i class="fas fa-location-arrow me-1"></i> <span class="distance-value">0</span> km dari Anda
                        </div>

                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <div>
                                <div style="font-size:0.68rem;color:var(--gray-500);">Mulai dari</div>
                                <div class="fw-bold text-primary" style="font-size:0.95rem;line-height:1.1;">
                                    Rp <?= number_format($h['price_start'], 0, ',', '.') ?>
                                    <span style="font-size:0.65rem;font-weight:400;color:var(--gray-500);">/malam</span>
                                </div>
                            </div>
                            <a href="<?= BASE_URL ?>/hotels/<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:0.78rem;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- VIEW 2: Leaflet Map View -->
    <div id="mapView" style="display:none;" data-aos="fade-in">
        <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
            <div id="hotelMap" style="width:100%;height:560px;background:#e2e8f0;"></div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
let map = null;
let hotelBounds = [];

function switchView(view) {
    const gridView = document.getElementById('gridView');
    const mapView  = document.getElementById('mapView');
    const btnGrid  = document.getElementById('btnViewGrid');
    const btnMap   = document.getElementById('btnViewMap');

    if (view === 'grid') {
        gridView.style.display = 'block';
        mapView.style.display  = 'none';
        btnGrid.classList.add('active');
        btnMap.classList.remove('active');
    } else {
        gridView.style.display = 'none';
        mapView.style.display  = 'block';
        btnGrid.classList.remove('active');
        btnMap.classList.add('active');

        if (!map) {
            initHotelMap();
        }
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
                if (hotelBounds.length > 0) {
                    map.fitBounds(hotelBounds, { padding: [40, 40] });
                }
            }
        }, 200);
    }
}

function initHotelMap() {
    map = L.map('hotelMap').setView([-6.6200, 106.8150], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const hotels = <?= json_encode($hotels) ?>;
    hotelBounds = [];

    hotels.forEach(h => {
        if (h.latitude && h.longitude) {
            const lat = parseFloat(h.latitude);
            const lng = parseFloat(h.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                hotelBounds.push([lat, lng]);

                const imgUrl = h.image_path ? '<?= BASE_URL ?>/' + h.image_path : '';
                const popupHtml = `
                    <div class="map-popup-card">
                        ${imgUrl ? `<img src="${imgUrl}" alt="${h.name}">` : ''}
                        <h5 style="margin-top:4px;font-size:0.9rem;font-weight:700;">${h.name}</h5>
                        <p style="margin-bottom:4px;font-size:0.75rem;color:#64748b;"><i class="fas fa-map-marker-alt text-primary me-1"></i>Dekat ${h.destination_name}</p>
                        <div class="fw-bold text-primary mb-2" style="font-size:0.85rem;">
                            Rp ${parseInt(h.price_start).toLocaleString('id-ID')}/malam
                        </div>
                        <a href="<?= BASE_URL ?>/hotels/${h.id}" class="btn btn-sm btn-primary w-100 rounded-pill" style="font-size:0.75rem;">
                            Lihat Detail Hotel
                        </a>
                    </div>
                `;

                L.marker([lat, lng]).addTo(map).bindPopup(popupHtml);
            }
        }
    });

    if (hotelBounds.length > 0) {
        map.fitBounds(hotelBounds, { padding: [40, 40] });
    }
}

// ── GEOLOCATION SORTING LOGIC FOR HOTELS ──
const btnGeoSort = document.getElementById('btnGeoSort');
if (btnGeoSort) {
    btnGeoSort.addEventListener('click', function() {
        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Didukung',
                text: 'Browser Anda tidak mendukung fitur Geolocation GPS.',
                confirmButtonColor: '#1a6bbf'
            });
            return;
        }

        const originalText = btnGeoSort.innerHTML;
        btnGeoSort.disabled = true;
        btnGeoSort.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mendeteksi Lokasi...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Hitung jarak untuk setiap hotel
                const items = document.querySelectorAll('.hotel-grid-item');
                const itemsArray = Array.from(items);

                itemsArray.forEach(item => {
                    const lat = parseFloat(item.getAttribute('data-lat'));
                    const lng = parseFloat(item.getAttribute('data-lng'));

                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                        const dist = getHaversineDistance(userLat, userLng, lat, lng);
                        item.setAttribute('data-distance', dist);
                        
                        // Tampilkan badge dan set teks jarak
                        const badge = item.querySelector('.geo-distance-badge');
                        if (badge) {
                            badge.classList.remove('d-none');
                            badge.querySelector('.distance-value').textContent = dist.toFixed(1);
                        }
                    } else {
                        // Jika tidak ada koordinat, berikan jarak yang sangat besar agar berada di paling bawah
                        item.setAttribute('data-distance', 999999);
                        const badge = item.querySelector('.geo-distance-badge');
                        if (badge) badge.classList.add('d-none');
                    }
                });

                // Urutkan item berdasarkan jarak terkecil (ASC)
                itemsArray.sort((a, b) => {
                    const distA = parseFloat(a.getAttribute('data-distance') || 999999);
                    const distB = parseFloat(b.getAttribute('data-distance') || 999999);
                    return distA - distB;
                });

                // Masukkan kembali item yang telah diurutkan ke grid container
                const gridContainer = document.querySelector('#gridView > .row');
                if (gridContainer) {
                    itemsArray.forEach(item => {
                        gridContainer.appendChild(item);
                    });
                }

                // Ubah gaya tombol menjadi aktif
                btnGeoSort.disabled = false;
                btnGeoSort.innerHTML = '<i class="fas fa-check-circle me-2"></i> Hotel Terdekat Aktif';
                btnGeoSort.className = 'btn btn-success w-100 rounded-3 py-2 fw-600';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil mengurutkan hotel terdekat!',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            },
            (error) => {
                btnGeoSort.disabled = false;
                btnGeoSort.innerHTML = originalText;
                
                let errorMsg = 'Gagal mengakses GPS lokal Anda.';
                if (error.code === error.PERMISSION_DENIED) {
                    errorMsg = 'Akses lokasi ditolak. Silakan berikan izin GPS di browser Anda.';
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Lokasi Gagal',
                    text: errorMsg,
                    confirmButtonColor: '#1a6bbf'
                });
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    });
}

function getHaversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi dalam km
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c; // Jarak dalam km
}

function deg2rad(deg) {
    return deg * (Math.PI/180);
}
</script>
