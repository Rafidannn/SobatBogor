<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/models/Category.php';

/**
 * MapController.php
 * Menangani halaman peta interaktif terpadu:
 * - index(): Halaman peta gabungan (destinasi + hotel) atau mode rute itinerary
 */
class MapController extends Controller {

    /**
     * GET /peta
     * Halaman peta interaktif penuh.
     */
    public function index(): void {
        $db            = Database::getInstance()->getConnection();
        $categoryModel = new Category();

        $itineraryId = (int)($_GET['itinerary_id'] ?? 0);
        $itineraryRoute = null;
        $itineraryInfo  = null;

        if ($itineraryId > 0) {
            // Cek session login untuk kepemilikan itinerary
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $currentUserId = (int)($_SESSION['user_id'] ?? 0);

            // Query header itinerary
            $itinStmt = $db->prepare("SELECT * FROM user_itineraries WHERE id = ? LIMIT 1");
            $itinStmt->execute([$itineraryId]);
            $itin = $itinStmt->fetch(PDO::FETCH_ASSOC);

            // Validasi: itinerary harus ada dan milik user (jika user login)
            if ($itin && ($currentUserId === 0 || (int)$itin['user_id'] === $currentUserId)) {
                $itineraryInfo = $itin;

                // Query rute destinasi berurutan per hari
                $routeStmt = $db->prepare(
                    "SELECT uii.day_number, uii.sort_order,
                            d.id, d.name, d.slug, d.address, d.latitude, d.longitude,
                            d.ticket_price, d.ticket_price_weekday,
                            c.name AS category_name, c.icon AS category_icon,
                            (SELECT image_path FROM destination_images
                             WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                            COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                                      WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                            (SELECT COUNT(*) FROM reviews
                             WHERE destination_id = d.id AND is_visible = 1) AS review_count
                     FROM user_itinerary_items uii
                     JOIN destinations d ON d.id = uii.destination_id
                     LEFT JOIN categories c ON c.id = d.category_id
                     WHERE uii.itinerary_id = ?
                       AND d.latitude IS NOT NULL
                       AND d.longitude IS NOT NULL
                     ORDER BY uii.day_number ASC, uii.sort_order ASC"
                );
                $routeStmt->execute([$itineraryId]);
                $rawItems = $routeStmt->fetchAll(PDO::FETCH_ASSOC);

                // Group per hari
                $grouped = [];
                foreach ($rawItems as $item) {
                    $grouped[(int)$item['day_number']][] = $item;
                }
                ksort($grouped);

                $itineraryRoute = [
                    'days' => $grouped,
                    'all'  => $rawItems,
                ];
            }
        }

        // Ambil semua destinasi yang punya koordinat (untuk mode normal / fallback)
        $destStmt = $db->query(
            "SELECT d.id, d.name, d.slug, d.address, d.latitude, d.longitude,
                    d.open_hours, d.ticket_price_weekday, d.ticket_price_weekend, d.ticket_price,
                    c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                    COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                              WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                    (SELECT COUNT(*) FROM reviews
                     WHERE destination_id = d.id AND is_visible = 1) AS review_count
             FROM destinations d
             LEFT JOIN categories c ON d.category_id = c.id
             WHERE d.latitude IS NOT NULL AND d.longitude IS NOT NULL
             ORDER BY d.name ASC"
        );
        $destinations = $destStmt->fetchAll(PDO::FETCH_ASSOC);

        // Ambil semua hotel aktif yang punya koordinat
        $hotelStmt = $db->query(
            "SELECT h.id, h.name, h.address, h.latitude, h.longitude,
                    h.star_rating, h.price_start, h.distance_text, h.image_path,
                    d.name AS destination_name, d.slug AS destination_slug
             FROM nearby_hotels h
             JOIN destinations d ON d.id = h.destination_id
             WHERE h.is_active = 1
               AND h.latitude IS NOT NULL
               AND h.longitude IS NOT NULL
             ORDER BY h.name ASC"
        );
        $hotels = $hotelStmt->fetchAll(PDO::FETCH_ASSOC);

        // Kategori untuk filter chip
        $categories = $categoryModel->getCategoriesWithCount();

        // Statistik singkat
        $stats = [
            'destinations_on_map' => count($destinations),
            'hotels_on_map'       => count($hotels),
        ];

        $pageTitle = $itineraryInfo
            ? 'Rute Peta: ' . htmlspecialchars($itineraryInfo['title'])
            : 'Peta Wisata Bogor';

        $this->view('map/index', [
            'title'          => $pageTitle,
            'metaDesc'       => 'Jelajahi rute perjalanan wisata di Bogor dalam peta interaktif.',
            'destinations'   => $destinations,
            'hotels'         => $hotels,
            'categories'     => $categories,
            'stats'          => $stats,
            'itineraryRoute' => $itineraryRoute,
            'itineraryInfo'  => $itineraryInfo,
        ]);
    }
}
