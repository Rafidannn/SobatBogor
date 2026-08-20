<?php
/**
 * app/views/map/index.php
 * Halaman Peta Interaktif Terpadu — Destinasi & Hotel Bogor
 */

// ─── Helper lokal ─────────────────────────────────────────────────────────────
function mapFormatPrice($price): string
{
    if (!$price || (float) $price == 0)
        return 'Gratis';
    return 'Rp ' . number_format((float) $price, 0, ',', '.');
}
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

<style>
    /* ── Page Layout ─────────────────────────────────────── */
    .map-page-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 70px);
        overflow: hidden;
    }

    /* ── Top Control Bar ─────────────────────────────────── */
    .map-control-bar {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.6rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        flex-shrink: 0;
        z-index: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    /* ── Search Box ─────────────────────────────────────── */
    .map-search-wrap {
        position: relative;
        flex: 0 0 260px;
    }

    .map-search-wrap input {
        width: 100%;
        padding: 0.42rem 1rem 0.42rem 2.2rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 50px;
        font-family: 'Outfit', sans-serif;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .map-search-wrap input:focus {
        border-color: #1a6bbf;
    }

    .map-search-wrap .fa-search {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.78rem;
    }

    /* ── Filter Chips ────────────────────────────────────── */
    .filter-chips {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.28rem 0.75rem;
        border-radius: 50px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.18s ease;
        user-select: none;
        white-space: nowrap;
    }

    .chip:hover {
        border-color: #1a6bbf;
        color: #1a6bbf;
        background: rgba(26, 107, 191, 0.05);
    }

    .chip.active {
        background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(26, 107, 191, 0.3);
    }

    .chip.chip-hotel.active {
        background: linear-gradient(135deg, #1a6bbf, #3b82f6);
        box-shadow: 0 2px 8px rgba(26, 107, 191, 0.3);
    }

    .chip.chip-all.active {
        background: linear-gradient(135deg, #0f172a, #334155);
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.3);
    }

    /* ── Stats Badge ─────────────────────────────────────── */
    .map-stats {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-left: auto;
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
    }

    .stat-badge.dest {
        background: rgba(26, 107, 191, 0.08);
        color: #1a6bbf;
        border: 1px solid rgba(26, 107, 191, 0.2);
    }

    .stat-badge.hotel {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    /* ── Split Layout ────────────────────────────────────── */
    .map-body {
        display: flex;
        flex: 1;
        overflow: hidden;
    }

    /* ── Sidebar (card list) ─────────────────────────────── */
    .map-sidebar {
        width: 330px;
        flex-shrink: 0;
        overflow-y: auto;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
    }

    .map-sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .map-sidebar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .map-sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .sidebar-list-header {
        padding: 0.75rem 1rem 0.5rem;
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .sidebar-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* ── Marker Card (sidebar item) ─────────────────────── */
    .marker-card {
        display: flex;
        align-items: stretch;
        gap: 0;
        padding: 0.7rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.15s;
        background: #fff;
        margin: 0.3rem 0.5rem;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .marker-card:hover {
        background: rgba(26, 107, 191, 0.05);
        box-shadow: 0 2px 8px rgba(26, 107, 191, 0.1);
    }

    .marker-card.highlighted {
        background: rgba(26, 107, 191, 0.07);
        border-left: 3px solid #1a6bbf;
        box-shadow: 0 3px 12px rgba(26, 107, 191, 0.15);
    }

    .marker-card.hotel-card-item:hover {
        background: #eff6ff;
        box-shadow: 0 2px 8px rgba(26, 107, 191, 0.1);
    }

    .marker-card.hotel-card-item.highlighted {
        background: #eff6ff;
        border-left-color: #1a6bbf;
    }

    .mc-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #e2e8f0;
    }

    .mc-thumb-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .mc-info {
        padding-left: 0.65rem;
        flex: 1;
        min-width: 0;
    }

    .mc-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 0.82rem;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .mc-sub {
        font-size: 0.72rem;
        color: #64748b;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mc-price {
        font-size: 0.75rem;
        font-weight: 700;
        color: #1a6bbf;
    }

    .mc-price.hotel {
        color: #1d4ed8;
    }

    .mc-category-badge {
        display: inline-block;
        font-size: 0.63rem;
        font-weight: 600;
        padding: 1px 6px;
        border-radius: 50px;
        background: rgba(26, 107, 191, 0.08);
        color: #1a6bbf;
        margin-bottom: 2px;
    }

    .mc-category-badge.hotel {
        background: #eff6ff;
        color: #1d4ed8;
    }

    /* ── Map Container ───────────────────────────────────── */
    #mainMap {
        flex: 1;
        z-index: 1;
    }

    /* ── Custom Leaflet Marker Icons ─────────────────────── */
    .custom-marker-dest {
        background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2.5px solid #fff;
        box-shadow: 0 3px 10px rgba(26, 107, 191, 0.5);
    }

    .custom-marker-hotel {
        background: linear-gradient(135deg, #1a6bbf, #3b82f6);
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2.5px solid #fff;
        box-shadow: 0 3px 10px rgba(26, 107, 191, 0.5);
    }

    .custom-marker-dest .marker-icon-inner,
    .custom-marker-hotel .marker-icon-inner {
        transform: rotate(45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 0.65rem;
        color: #fff;
    }

    /* ── Popup Card Styles ───────────────────────────────── */
    .leaflet-popup-content-wrapper {
        border-radius: 14px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        padding: 0 !important;
        overflow: hidden;
    }

    .leaflet-popup-content {
        margin: 0 !important;
        width: 230px !important;
    }

    .leaflet-popup-tip {
        background: #fff !important;
    }

    .popup-card-img {
        width: 100%;
        height: 110px;
        object-fit: cover;
    }

    .popup-img-placeholder {
        width: 100%;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .popup-card-body {
        padding: 0.75rem 0.9rem;
        font-family: 'Outfit', sans-serif;
    }

    .popup-card-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 3px;
        line-height: 1.3;
    }

    .popup-card-sub {
        font-size: 0.73rem;
        color: #64748b;
        margin-bottom: 5px;
    }

    .popup-card-price {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1a6bbf;
        margin-bottom: 8px;
    }

    .popup-card-price.hotel {
        color: #1d4ed8;
    }

    .popup-btn {
        display: block;
        text-align: center;
        padding: 0.38rem 0.75rem;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(135deg, #1a6bbf, #3a9e3a);
        transition: opacity 0.2s;
    }

    .popup-btn:hover {
        opacity: 0.88;
        color: #fff;
    }

    .popup-btn.hotel {
        background: linear-gradient(135deg, #1a6bbf, #3b82f6);
    }

    /* ── Locate Me Button ────────────────────────────────── */
    .map-locate-btn {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 50px;
        padding: 0.4rem 0.9rem;
        font-family: 'Outfit', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
        cursor: pointer;
        transition: all 0.18s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .map-locate-btn:hover {
        border-color: #1a6bbf;
        color: #1a6bbf;
        background: #eff6ff;
    }

    .map-locate-btn.locating {
        border-color: #f59e0b;
        color: #d97706;
        background: #fffbeb;
    }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 768px) {
        .map-page-wrapper {
            height: auto;
        }

        .map-body {
            flex-direction: column;
        }

        .map-sidebar {
            width: 100%;
            height: 250px;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }

        #mainMap {
            height: 55vh;
        }

        .map-stats {
            display: none;
        }
    }
</style>

<!-- ════════════════════════════════════════════
     PETA INTERAKTIF LAYOUT
════════════════════════════════════════════ -->
<div class="map-page-wrapper">

    <!-- ── TOP CONTROL BAR ──────────────────────────── -->
    <div class="map-control-bar">

        <?php if (isset($itineraryRoute) && $itineraryRoute && isset($itineraryInfo)): ?>
            <!-- Mode Rute Itinerary Active Banner -->
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 py-1.5 px-3 mb-1"
                style="background:linear-gradient(135deg, #0d1529 0%, #1a2a4a 100%);border-radius:14px;color:#fff;box-shadow:0 4px 14px rgba(0,0,0,0.15);">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge"
                        style="background:linear-gradient(135deg,#1a6bbf,#3a9e3a);font-weight:800;font-size:0.75rem;padding:0.3rem 0.75rem;border-radius:20px;letter-spacing:0.5px;">
                        <i class="fas fa-route me-1"></i>MODE RUTE ITINERARY
                    </span>
                    <strong
                        style="font-size:1rem;color:#fff;font-weight:800;"><?= htmlspecialchars($itineraryInfo['title']) ?></strong>
                    <span style="font-size:0.8rem;color:rgba(255,255,255,0.65);">
                        (<?= count($itineraryRoute['all']) ?> Destinasi · <?= count($itineraryRoute['days']) ?> Hari)
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= BASE_URL ?>/itinerary/builder/<?= (int) $itineraryInfo['id'] ?>"
                        class="btn btn-sm text-white font-weight-bold"
                        style="background:#1a6bbf;border-radius:8px;font-weight:700;font-size:0.8rem;padding:0.35rem 0.85rem;">
                        <i class="fas fa-edit me-1"></i>Edit di Builder
                    </a>
                    <a href="<?= BASE_URL ?>/peta" class="btn btn-sm btn-outline-light"
                        style="border-radius:8px;font-size:0.8rem;padding:0.35rem 0.85rem;">
                        <i class="fas fa-times me-1"></i>Tutup Mode Rute
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Search -->
        <div class="map-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="mapSearchInput" placeholder="Cari destinasi atau hotel...">
        </div>

        <!-- Filter Chips -->
        <div class="filter-chips">
            <button class="chip chip-all active" data-filter="all" id="chipAll">
                <i class="fas fa-globe-asia"></i> Semua
            </button>
            <button class="chip" data-filter="destinations" id="chipDest">
                <i class="fas fa-mountain"></i> Wisata
            </button>
            <button class="chip chip-hotel" data-filter="hotels" id="chipHotel">
                <i class="fas fa-hotel"></i> Hotel
            </button>

            <!-- Separator -->
            <div style="width:1px;height:20px;background:#e2e8f0;margin:0 0.2rem;"></div>

            <?php foreach ($categories as $cat): ?>
                <button class="chip chip-cat" data-cat="<?= htmlspecialchars($cat['slug']) ?>">
                    <i class="fas fa-<?= htmlspecialchars($cat['icon'] ?? 'map-pin') ?>"></i>
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Locate Me Button -->
        <button class="map-locate-btn" id="locateMeBtn" title="Tampilkan Lokasi Saya">
            <i class="fas fa-crosshairs"></i>
            <span class="d-none d-md-inline">Lokasi Saya</span>
        </button>

        <!-- Stats -->
        <div class="map-stats">
            <span class="stat-badge dest">
                <i class="fas fa-mountain"></i>
                <span id="destCountBadge"><?= $stats['destinations_on_map'] ?></span> Wisata
            </span>
            <span class="stat-badge hotel">
                <i class="fas fa-hotel"></i>
                <span id="hotelCountBadge"><?= $stats['hotels_on_map'] ?></span> Hotel
            </span>
        </div>
    </div>

    <!-- ── BODY (Sidebar + Map) ──────────────────────── -->
    <div class="map-body">

        <!-- ── SIDEBAR ──────────────────────── -->
        <div class="map-sidebar" id="mapSidebar">
            <div class="sidebar-list-header" id="sidebarHeader">
                Semua Tempat (<span id="sidebarCount"><?= count($destinations) + count($hotels) ?></span>)
            </div>
            <div id="sidebarList">
                <!-- Diisi oleh JS -->
            </div>
        </div>

        <!-- ── MAP ──────────────────────── -->
        <div id="mainMap"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // ════════════════════════════════════════════════════════════
    //  DATA dari PHP (JSON)
    // ════════════════════════════════════════════════════════════
    const BASE_URL = '<?= BASE_URL ?>';
    const DESTINATIONS = <?= json_encode(array_values($destinations), JSON_UNESCAPED_UNICODE) ?>;
    const HOTELS = <?= json_encode(array_values($hotels), JSON_UNESCAPED_UNICODE) ?>;
    const ITINERARY_ROUTE = <?= isset($itineraryRoute) && $itineraryRoute ? json_encode($itineraryRoute, JSON_UNESCAPED_UNICODE) : 'null' ?>;
    const ITINERARY_INFO = <?= isset($itineraryInfo) && $itineraryInfo ? json_encode($itineraryInfo, JSON_UNESCAPED_UNICODE) : 'null' ?>;

    // Day Palette
    const DAY_COLORS = ['#1a6bbf', '#3a9e3a', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'];

    // ════════════════════════════════════════════════════════════
    //  LEAFLET MAP INIT
    // ════════════════════════════════════════════════════════════
    const map = L.map('mainMap', {
        center: [-6.5981, 106.7994],
        zoom: 12,
        zoomControl: false
    });

    // Zoom control — posisi kanan bawah
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Tile Layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    // ════════════════════════════════════════════════════════════
    //  CUSTOM MARKER ICON FACTORY
    // ════════════════════════════════════════════════════════════
    function makeDestIcon(categoryIcon) {
        const icon = categoryIcon || 'map-marker-alt';
        return L.divIcon({
            className: '',
            html: `<div style="
            width:34px;height:34px;
            background:linear-gradient(135deg,#1a6bbf,#3a9e3a);
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            border:2.5px solid #fff;
            box-shadow:0 4px 12px rgba(26,107,191,0.45);
            display:flex;align-items:center;justify-content:center;">
            <i class='fas fa-${icon}' style='transform:rotate(45deg);color:#fff;font-size:0.65rem;'></i>
        </div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -36]
        });
    }

    function makeHotelIcon(stars) {
        return L.divIcon({
            className: '',
            html: `<div style="
            width:34px;height:34px;
            background:linear-gradient(135deg,#1a6bbf,#3b82f6);
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            border:2.5px solid #fff;
            box-shadow:0 4px 12px rgba(26,107,191,0.45);
            display:flex;align-items:center;justify-content:center;">
            <i class='fas fa-hotel' style='transform:rotate(45deg);color:#fff;font-size:0.65rem;'></i>
        </div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -36]
        });
    }

    function makeNumberedMarkerIcon(seq, color) {
        return L.divIcon({
            className: '',
            html: `<div style="
            width:32px;height:32px;
            background:${color};
            border-radius:50%;
            border:2.5px solid #fff;
            box-shadow:0 4px 12px rgba(0,0,0,0.35);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-weight:800;font-family:'Outfit',sans-serif;font-size:0.85rem;">
            ${seq}
        </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -18]
        });
    }

    // ════════════════════════════════════════════════════════════
    //  FORMAT HELPERS
    // ════════════════════════════════════════════════════════════
    function formatRupiah(val) {
        const n = parseFloat(val);
        if (!n || n === 0) return 'Gratis';
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function renderStars(rating) {
        const r = Math.round(parseFloat(rating) || 0);
        let html = '';
        for (let i = 1; i <= 5; i++) {
            html += i <= r
                ? '<i class="fas fa-star" style="color:#f59e0b;font-size:0.65rem;"></i>'
                : '<i class="far fa-star" style="color:#d1d5db;font-size:0.65rem;"></i>';
        }
        return html;
    }

    // ════════════════════════════════════════════════════════════
    //  POPUP HTML BUILDERS
    // ════════════════════════════════════════════════════════════
    function buildDestPopup(d) {
        const img = d.primary_image
            ? `<img class="popup-card-img" src="${BASE_URL}/${d.primary_image}" alt="${d.name}">`
            : `<div class="popup-img-placeholder" style="background:linear-gradient(135deg,#fed7aa,#fb923c);">
               <i class="fas fa-mountain"></i>
           </div>`;

        const isCulinary = (parseInt(d.category_id) === 3 || (d.category_name || '').toLowerCase() === 'kuliner');
        const priceUnit = isCulinary ? '/ porsi' : '/ orang';

        const price = d.ticket_price_weekday
            ? formatRupiah(d.ticket_price_weekday)
            : (d.ticket_price ? formatRupiah(d.ticket_price) : 'Gratis');

        return `<div>
        ${img}
        <div class="popup-card-body">
            <span style="font-size:0.65rem;font-weight:700;color:#1a6bbf;background:rgba(26,107,191,0.09);padding:1px 7px;border-radius:50px;display:inline-block;margin-bottom:4px;">
                ${d.category_name || 'Wisata'}
            </span>
            <div class="popup-card-title">${d.name}</div>
            <div class="popup-card-sub"><i class="fas fa-map-marker-alt" style="color:#ef4444;margin-right:3px;"></i>${(d.address || 'Bogor').split(',')[0]}</div>
            <div class="popup-card-price">${price} ${priceUnit}</div>
            <div style="display:flex;align-items:center;gap:5px;margin-bottom:8px;">
                ${renderStars(d.avg_rating)}
                <span style="font-size:0.68rem;color:#64748b;">(${d.review_count || 0})</span>
            </div>
            <div style="display:flex;gap:6px;">
                <a href="${BASE_URL}/destinations/${d.slug}" class="popup-btn" style="flex:1;">
                    <i class="fas fa-info-circle me-1"></i>Detail
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query=${d.latitude},${d.longitude}"
                   target="_blank" class="popup-btn"
                   style="flex:1;background:linear-gradient(135deg,#16a34a,#22c55e);">
                    <i class="fas fa-directions me-1"></i>Rute
                </a>
            </div>
        </div>
    </div>`;
    }

    function buildHotelPopup(h) {
        const img = h.image_path
            ? `<img class="popup-card-img" src="${BASE_URL}/${h.image_path}" alt="${h.name}">`
            : `<div class="popup-img-placeholder" style="background:linear-gradient(135deg,#bfdbfe,#3b82f6);">
               <i class="fas fa-hotel"></i>
           </div>`;

        const stars = '★'.repeat(parseInt(h.star_rating) || 0);

        return `<div>
        ${img}
        <div class="popup-card-body">
            <span style="font-size:0.65rem;font-weight:700;color:#1d4ed8;background:#eff6ff;padding:1px 7px;border-radius:50px;display:inline-block;margin-bottom:4px;">
                ${stars} Hotel
            </span>
            <div class="popup-card-title">${h.name}</div>
            <div class="popup-card-sub"><i class="fas fa-map-marker-alt" style="color:#1a6bbf;margin-right:3px;"></i>Dekat ${h.destination_name}</div>
            <div class="popup-card-price hotel">Mulai Rp ${parseInt(h.price_start).toLocaleString('id-ID')} <span style="font-weight:400;font-size:0.7rem;color:#64748b;">/malam</span></div>
            <div style="display:flex;gap:6px;">
                <a href="${BASE_URL}/hotels/${h.id}" class="popup-btn hotel" style="flex:1;">
                    <i class="fas fa-info-circle me-1"></i>Detail
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query=${h.latitude},${h.longitude}"
                   target="_blank" class="popup-btn"
                   style="flex:1;background:linear-gradient(135deg,#16a34a,#22c55e);">
                    <i class="fas fa-directions me-1"></i>Rute
                </a>
            </div>
        </div>
    </div>`;
    }

    // ════════════════════════════════════════════════════════════
    //  MARKER & SIDEBAR MANAGEMENT
    // ════════════════════════════════════════════════════════════
    let destMarkers = [];  // [{marker, leaflet, data}]
    let hotelMarkers = [];  // [{marker, leaflet, data}]
    let itinMarkers = [];  // [{leaflet, data, dayNum, seqNum}]
    let destLayer = null;
    let hotelLayer = null;
    let userMarker = null;

    if (ITINERARY_ROUTE && ITINERARY_ROUTE.days) {
        const allItinCoords = [];
        const container = document.getElementById('sidebarList');
        const header = document.getElementById('sidebarHeader');
        if (header && ITINERARY_INFO) {
            header.innerHTML = `<i class="fas fa-route text-primary me-1"></i> ${ITINERARY_INFO.title}`;
        }

        let sidebarHtml = '';

        Object.keys(ITINERARY_ROUTE.days).forEach(dayKey => {
            const dayNum = parseInt(dayKey, 10);
            const color = DAY_COLORS[(dayNum - 1) % DAY_COLORS.length];
            const items = ITINERARY_ROUTE.days[dayKey];
            const dayCoords = [];

            sidebarHtml += `<div class="sidebar-day-group mb-3">
            <div style="font-size:0.78rem;font-weight:800;color:${color};background:rgba(0,0,0,0.03);padding:0.4rem 0.75rem;border-radius:8px;border-left:3.5px solid ${color};margin-bottom:0.5rem;" class="d-flex align-items-center justify-content-between">
                <span>HARI KE-${dayNum}</span>
                <span class="badge" style="background:${color};">${items.length} Destinasi</span>
            </div>`;

            items.forEach((item, idx) => {
                if (!item.latitude || !item.longitude) return;
                const seqNum = idx + 1;
                const coord = [parseFloat(item.latitude), parseFloat(item.longitude)];
                dayCoords.push(coord);
                allItinCoords.push(coord);

                const lm = L.marker(coord, {
                    icon: makeNumberedMarkerIcon(seqNum, color)
                }).bindPopup(buildDestPopup(item), { maxWidth: 240 }).addTo(map);

                const markerIndex = itinMarkers.length;
                itinMarkers.push({ leaflet: lm, data: item, dayNum: dayNum, seqNum: seqNum });

                const price = item.ticket_price_weekday ? formatRupiah(item.ticket_price_weekday)
                    : (item.ticket_price ? formatRupiah(item.ticket_price) : 'Gratis');
                const img = item.primary_image
                    ? `<img class="mc-thumb" src="${BASE_URL}/${item.primary_image}" alt="${item.name}">`
                    : `<div class="mc-thumb-placeholder" style="background:linear-gradient(135deg,#dbeafe,#1a6bbf);"><i class="fas fa-mountain" style="color:rgba(255,255,255,0.7);"></i></div>`;

                sidebarHtml += `<div class="marker-card" data-id="itin-${markerIndex}" onclick="focusItinMarker(${markerIndex})">
                <div style="width:24px;height:24px;border-radius:50%;background:${color};color:#fff;font-weight:800;font-size:0.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    ${seqNum}
                </div>
                ${img}
                <div class="mc-info">
                    <div class="mc-category-badge">${item.category_name || 'Wisata'}</div>
                    <div class="mc-name">${item.name}</div>
                    <div class="mc-price">${price}</div>
                </div>
            </div>`;
            });

            sidebarHtml += `</div>`;

            // Hubungkan destinasi hari ini dengan polyline
            if (dayCoords.length > 1) {
                L.polyline(dayCoords, {
                    color: color,
                    weight: 4.5,
                    opacity: 0.85,
                    dashArray: '6, 6'
                }).addTo(map);
            }
        });

        if (container) container.innerHTML = sidebarHtml;

        if (allItinCoords.length > 0) {
            map.fitBounds(allItinCoords, { padding: [50, 50] });
        }
    } else {
        // Mode Peta Normal (Semua Wisata + Hotel)
        DESTINATIONS.forEach(d => {
            if (!d.latitude || !d.longitude) return;
            const lm = L.marker([parseFloat(d.latitude), parseFloat(d.longitude)], {
                icon: makeDestIcon(d.category_icon)
            }).bindPopup(buildDestPopup(d), { maxWidth: 240 });
            destMarkers.push({ leaflet: lm, data: d });
        });

        HOTELS.forEach(h => {
            if (!h.latitude || !h.longitude) return;
            const lm = L.marker([parseFloat(h.latitude), parseFloat(h.longitude)], {
                icon: makeHotelIcon(h.star_rating)
            }).bindPopup(buildHotelPopup(h), { maxWidth: 240 });
            hotelMarkers.push({ leaflet: lm, data: h });
        });

        destLayer = L.layerGroup(destMarkers.map(m => m.leaflet)).addTo(map);
        hotelLayer = L.layerGroup(hotelMarkers.map(m => m.leaflet)).addTo(map);

        const allCoords = [
            ...destMarkers.map(m => [parseFloat(m.data.latitude), parseFloat(m.data.longitude)]),
            ...hotelMarkers.map(m => [parseFloat(m.data.latitude), parseFloat(m.data.longitude)])
        ];
        if (allCoords.length > 0) {
            map.fitBounds(allCoords, { padding: [40, 40] });
        }
    }

    function focusItinMarker(idx) {
        document.querySelectorAll('.marker-card').forEach(c => c.classList.remove('highlighted'));
        const m = itinMarkers[idx];
        if (!m) return;
        document.querySelector(`[data-id="itin-${idx}"]`)?.classList.add('highlighted');
        map.flyTo([parseFloat(m.data.latitude), parseFloat(m.data.longitude)], 15, { animate: true, duration: 0.8 });
        setTimeout(() => m.leaflet.openPopup(), 850);
    }

    // ════════════════════════════════════════════════════════════
    //  SIDEBAR BUILDER
    // ════════════════════════════════════════════════════════════
    function buildSidebar(filteredDest, filteredHotel) {
        const container = document.getElementById('sidebarList');
        const header = document.getElementById('sidebarCount');
        const total = filteredDest.length + filteredHotel.length;
        header.textContent = total;

        if (total === 0) {
            container.innerHTML = `<div class="sidebar-empty">
            <i class="fas fa-map-pin fa-2x mb-2" style="color:#cbd5e1;"></i>
            <br>Tidak ada tempat yang cocok
        </div>`;
            return;
        }

        let html = '';

        filteredDest.forEach(m => {
            const d = m.data;
            const price = d.ticket_price_weekday ? formatRupiah(d.ticket_price_weekday)
                : (d.ticket_price ? formatRupiah(d.ticket_price) : 'Gratis');
            const img = d.primary_image
                ? `<img class="mc-thumb" src="${BASE_URL}/${d.primary_image}" alt="${d.name}">`
                : `<div class="mc-thumb-placeholder" style="background:linear-gradient(135deg,#dbeafe,#1a6bbf);">
                   <i class="fas fa-mountain" style="color:rgba(255,255,255,0.7);"></i>
               </div>`;
            html += `<div class="marker-card" data-id="dest-${d.id}" onclick="focusMarker('dest',${destMarkers.indexOf(m)})">
            ${img}
            <div class="mc-info">
                <div class="mc-category-badge">${d.category_name || 'Wisata'}</div>
                <div class="mc-name">${d.name}</div>
                <div class="mc-sub"><i class="fas fa-map-marker-alt" style="color:#ef4444;"></i> ${(d.address || 'Bogor').split(',')[0]}</div>
                <div class="mc-price">${price}</div>
            </div>
        </div>`;
        });

        filteredHotel.forEach(m => {
            const h = m.data;
            const img = h.image_path
                ? `<img class="mc-thumb" src="${BASE_URL}/${h.image_path}" alt="${h.name}">`
                : `<div class="mc-thumb-placeholder" style="background:linear-gradient(135deg,#bfdbfe,#3b82f6);">
                   <i class="fas fa-hotel" style="color:rgba(255,255,255,0.7);"></i>
               </div>`;
            const stars = '★'.repeat(parseInt(h.star_rating) || 0);
            html += `<div class="marker-card hotel-card-item" data-id="hotel-${h.id}" onclick="focusMarker('hotel',${hotelMarkers.indexOf(m)})">
            ${img}
            <div class="mc-info">
                <div class="mc-category-badge hotel">${stars} Hotel</div>
                <div class="mc-name">${h.name}</div>
                <div class="mc-sub"><i class="fas fa-map-marker-alt" style="color:#1a6bbf;"></i> Dekat ${h.destination_name}</div>
                <div class="mc-price hotel">Rp ${parseInt(h.price_start).toLocaleString('id-ID')}/malam</div>
            </div>
        </div>`;
        });

        container.innerHTML = html;
    }

    // ════════════════════════════════════════════════════════════
    //  FOCUS MARKER (klik sidebar → zoom ke marker)
    // ════════════════════════════════════════════════════════════
    function focusMarker(type, idx) {
        // Remove highlight
        document.querySelectorAll('.marker-card').forEach(c => c.classList.remove('highlighted'));

        let m;
        if (type === 'dest') {
            m = destMarkers[idx];
            document.querySelector(`[data-id="dest-${m.data.id}"]`)?.classList.add('highlighted');
        } else {
            m = hotelMarkers[idx];
            document.querySelector(`[data-id="hotel-${m.data.id}"]`)?.classList.add('highlighted');
        }
        if (!m) return;

        map.flyTo([parseFloat(m.data.latitude), parseFloat(m.data.longitude)], 15, {
            animate: true, duration: 0.8
        });
        setTimeout(() => m.leaflet.openPopup(), 850);
    }

    // ════════════════════════════════════════════════════════════
    //  FILTER LOGIC
    // ════════════════════════════════════════════════════════════
    let activeFilter = 'all';
    let activeCatSlug = null;
    let searchQuery = '';

    function applyFilters() {
        if (ITINERARY_ROUTE) return; // Mode itinerary: abaikan filter standar

        const q = searchQuery.toLowerCase().trim();

        // Filter destinations
        let visibleDest = destMarkers.filter(m => {
            const d = m.data;
            if (activeFilter === 'hotels') return false;
            if (activeCatSlug && d.category_slug !== activeCatSlug) return false;
            if (q && !d.name.toLowerCase().includes(q) && !(d.address || '').toLowerCase().includes(q)) return false;
            return true;
        });

        // Filter hotels
        let visibleHotel = hotelMarkers.filter(m => {
            const h = m.data;
            if (activeFilter === 'destinations') return false;
            if (activeCatSlug) return false; // Category filter hanya untuk wisata
            if (q && !h.name.toLowerCase().includes(q) && !(h.address || '').toLowerCase().includes(q)
                && !h.destination_name.toLowerCase().includes(q)) return false;
            return true;
        });

        // Update layers
        if (destLayer) {
            destLayer.clearLayers();
            visibleDest.forEach(m => destLayer.addLayer(m.leaflet));
        }

        if (hotelLayer) {
            hotelLayer.clearLayers();
            visibleHotel.forEach(m => hotelLayer.addLayer(m.leaflet));
        }

        // Update stat badges
        const destBadge = document.getElementById('destCountBadge');
        const hotelBadge = document.getElementById('hotelCountBadge');
        if (destBadge) destBadge.textContent = visibleDest.length;
        if (hotelBadge) hotelBadge.textContent = visibleHotel.length;

        // Rebuild sidebar
        buildSidebar(visibleDest, visibleHotel);

        // Fit bounds if results > 0
        const visible = [...visibleDest, ...visibleHotel];
        if (visible.length > 0) {
            const coords = visible.map(m => [
                parseFloat(m.data.latitude), parseFloat(m.data.longitude)
            ]);
            if (q || activeCatSlug) {
                map.fitBounds(coords, { padding: [60, 60], maxZoom: 14 });
            }
        }
    }

    // ── Filter Chip Click ──────────────────────────────────────
    document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', function () {
            const filter = this.dataset.filter;
            const cat = this.dataset.cat;

            if (filter) {
                // Main type filter
                activeFilter = filter;
                activeCatSlug = null;

                document.querySelectorAll('.chip[data-filter]').forEach(c => c.classList.remove('active'));
                document.querySelectorAll('.chip[data-cat]').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            } else if (cat) {
                // Category filter (only destinations)
                if (activeCatSlug === cat) {
                    // Toggle off
                    activeCatSlug = null;
                    this.classList.remove('active');
                    // Restore 'all' chip
                    document.getElementById('chipAll').classList.add('active');
                    activeFilter = 'all';
                } else {
                    activeCatSlug = cat;
                    activeFilter = 'destinations';

                    document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
                    document.getElementById('chipDest').classList.add('active');
                    this.classList.add('active');
                }
            }

            applyFilters();
        });
    });

    // ── Search Input ───────────────────────────────────────────
    document.getElementById('mapSearchInput').addEventListener('input', function () {
        searchQuery = this.value;
        applyFilters();
    });

    // ════════════════════════════════════════════════════════════
    //  LOCATE ME
    // ════════════════════════════════════════════════════════════
    document.getElementById('locateMeBtn').addEventListener('click', function () {
        const btn = this;
        btn.classList.add('locating');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="d-none d-md-inline">Mencari...</span>';

        if (!navigator.geolocation) {
            alert('Browser kamu tidak mendukung geolokasi.');
            btn.classList.remove('locating');
            btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span class="d-none d-md-inline">Lokasi Saya</span>';
            return;
        }

        navigator.geolocation.getCurrentPosition(pos => {
            const { latitude: lat, longitude: lng } = pos.coords;

            if (userMarker) map.removeLayer(userMarker);

            userMarker = L.circleMarker([lat, lng], {
                radius: 10,
                color: '#fff',
                weight: 3,
                fillColor: '#1d4ed8',
                fillOpacity: 1
            }).addTo(map).bindPopup('<div style="font-family:Outfit,sans-serif;font-size:0.82rem;font-weight:700;padding:4px 8px;"><i class="fas fa-user-circle" style="color:#1d4ed8;margin-right:5px;"></i>Lokasi Kamu</div>').openPopup();

            map.flyTo([lat, lng], 14, { animate: true, duration: 1 });

            btn.classList.remove('locating');
            btn.innerHTML = '<i class="fas fa-crosshairs" style="color:#16a34a;"></i> <span class="d-none d-md-inline" style="color:#16a34a;">Ditemukan!</span>';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span class="d-none d-md-inline">Lokasi Saya</span>';
            }, 3000);
        }, err => {
            alert('Tidak bisa mendapatkan lokasi: ' + err.message);
            btn.classList.remove('locating');
            btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span class="d-none d-md-inline">Lokasi Saya</span>';
        });
    });

    // ════════════════════════════════════════════════════════════
    //  INIT
    // ════════════════════════════════════════════════════════════
    applyFilters(); // build initial sidebar
</script>