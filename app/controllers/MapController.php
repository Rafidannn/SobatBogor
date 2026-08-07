<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/models/Category.php';

/**
 * MapController.php
 * Menangani halaman peta interaktif terpadu:
 * - index(): Halaman peta gabungan (destinasi + hotel)
 * - apiMarkers(): Endpoint JSON untuk data marker (AJAX)
 */
class MapController extends Controller {

    /**
     * GET /peta
     * Halaman peta interaktif penuh.
     */
    public function index(): void {
        $db            = Database::getInstance()->getConnection();
        $categoryModel = new Category();

        // Ambil semua destinasi yang punya koordinat
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

        $this->view('map/index', [
            'title'        => 'Peta Wisata Bogor',
            'metaDesc'     => 'Jelajahi seluruh destinasi wisata dan penginapan di Bogor dalam satu peta interaktif yang lengkap.',
            'destinations' => $destinations,
            'hotels'       => $hotels,
            'categories'   => $categories,
            'stats'        => $stats,
        ]);
    }
}
