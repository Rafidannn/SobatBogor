<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/Destination.php';
require_once ROOT_PATH . '/app/models/Category.php';

/**
 * HomeController.php
 * Menangani halaman beranda publik:
 * - Menampilkan destinasi featured / trending
 * - Menampilkan daftar kategori
 * - Statistik ringkas (jumlah destinasi, kategori, review)
 */
class HomeController extends Controller {

    public function index(): void {
        $destinationModel = new Destination();
        $categoryModel    = new Category();
        $db               = Database::getInstance()->getConnection();

        // Destinasi featured (is_featured = 1), maks 6
        $featuredStmt = $db->query(
            "SELECT d.*, c.name AS category_name,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                    COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                    (SELECT COUNT(*) FROM reviews WHERE destination_id = d.id AND is_visible = 1) AS review_count
             FROM destinations d
             LEFT JOIN categories c ON d.category_id = c.id
             WHERE d.is_featured = 1
             ORDER BY d.id DESC
             LIMIT 6"
        );
        $featuredDestinations = $featuredStmt->fetchAll(PDO::FETCH_ASSOC);

        // Semua destinasi terbaru (untuk "Temukan Wisata Baru")
        $recentStmt = $db->query(
            "SELECT d.*, c.name AS category_name,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                    COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                    (SELECT COUNT(*) FROM reviews WHERE destination_id = d.id AND is_visible = 1) AS review_count
             FROM destinations d
             LEFT JOIN categories c ON d.category_id = c.id
             ORDER BY d.id DESC
             LIMIT 8"
        );
        $recentDestinations = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

        // Kategori + jumlah destinasi
        $categories = $categoryModel->getCategoriesWithCount();

        // Statistik untuk angka strip
        $stats = [
            'destinations' => $db->query("SELECT COUNT(*) FROM destinations")->fetchColumn(),
            'categories'   => $db->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'reviews'      => $db->query("SELECT COUNT(*) FROM reviews WHERE is_visible = 1")->fetchColumn(),
            'users'        => $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(),
        ];

        // Wishlist IDs milik user yang sedang login (untuk warna tombol hati)
        $wishlistIds = [];
        if (isset($_SESSION['user_id'])) {
            $wStmt = $db->prepare("SELECT destination_id FROM wishlists WHERE user_id = ?");
            $wStmt->execute([$_SESSION['user_id']]);
            $wishlistIds = array_column($wStmt->fetchAll(PDO::FETCH_ASSOC), 'destination_id');
        }

        $this->view('home/index', [
            'title'                => 'Beranda',
            'metaDesc'             => 'Temukan ratusan destinasi wisata terbaik di Bogor — dari alam, kuliner, budaya, hingga keluarga.',
            'featuredDestinations' => $featuredDestinations,
            'recentDestinations'   => $recentDestinations,
            'categories'           => $categories,
            'stats'                => $stats,
            'wishlistIds'          => $wishlistIds,
        ]);
    }
}
