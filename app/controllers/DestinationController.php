<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/Destination.php';
require_once ROOT_PATH . '/app/models/Category.php';
require_once ROOT_PATH . '/app/models/Review.php';
require_once ROOT_PATH . '/app/models/DestinationImage.php';
require_once ROOT_PATH . '/app/models/DestinationLink.php';
require_once ROOT_PATH . '/app/models/Hotel.php';

/**
 * DestinationController.php
 * Menangani halaman katalog dan detail destinasi wisata publik.
 */
class DestinationController extends Controller {

    /**
     * GET /destinations
     * Menampilkan katalog dengan filter: search, kategori, harga, urutan.
     */
    public function catalog(): void {
        $db             = Database::getInstance()->getConnection();
        $categoryModel  = new Category();

        // Input filter dari query string
        $search   = trim($_GET['q']        ?? '');
        $catSlug  = trim($_GET['category'] ?? '');
        $sort     = trim($_GET['sort']     ?? 'terbaru');
        $minPrice = (int)($_GET['min_price'] ?? 0);
        $maxPrice = (int)($_GET['max_price'] ?? 0);

        // Base query
        $where  = [];
        $params = [];

        if ($search !== '') {
            $where[]  = "(d.name LIKE :q1 OR d.address LIKE :q2 OR d.description LIKE :q3)";
            $params[':q1'] = "%{$search}%";
            $params[':q2'] = "%{$search}%";
            $params[':q3'] = "%{$search}%";
        }

        $categoryId = null;
        if ($catSlug !== '') {
            $cat = $categoryModel->findBySlug($catSlug);
            if ($cat) {
                $categoryId = $cat['id'];
                $where[]    = "d.category_id = :cat_id";
                $params[':cat_id'] = $categoryId;
            }
        } else {
            // Jika tanpa filter kategori di katalog umum Wisata, kecualikan Kuliner (ID 3)
            $where[] = "d.category_id != 3";
        }

        if ($minPrice > 0) {
            $where[] = "(d.ticket_price >= :min_price OR d.ticket_price IS NULL)";
            $params[':min_price'] = $minPrice;
        }
        if ($maxPrice > 0) {
            $where[] = "d.ticket_price <= :max_price";
            $params[':max_price'] = $maxPrice;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $orderMap = [
            'terbaru'    => 'd.id DESC',
            'terlama'    => 'd.id ASC',
            'rating'     => 'avg_rating DESC',
            'harga_asc'  => 'd.ticket_price ASC',
            'harga_desc' => 'd.ticket_price DESC',
            'nama'       => 'd.name ASC',
        ];
        $orderBy = $orderMap[$sort] ?? 'd.id DESC';

        $sql = "SELECT d.*, c.name AS category_name,
                       (SELECT image_path FROM destination_images
                        WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                       COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                                 WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                       (SELECT COUNT(*) FROM reviews
                        WHERE destination_id = d.id AND is_visible = 1) AS review_count
                FROM destinations d
                LEFT JOIN categories c ON d.category_id = c.id
                {$whereClause}
                ORDER BY {$orderBy}";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Semua kategori untuk sidebar filter
        $categories = $categoryModel->getCategoriesWithCount();

        // Wishlist IDs milik user yang login
        $wishlistIds = [];
        if (isset($_SESSION['user_id'])) {
            $wStmt = $db->prepare("SELECT destination_id FROM wishlists WHERE user_id = ?");
            $wStmt->execute([$_SESSION['user_id']]);
            $wishlistIds = array_column($wStmt->fetchAll(PDO::FETCH_ASSOC), 'destination_id');
        }

        $this->view('destinations/catalog', [
            'title'        => 'Katalog Wisata Bogor',
            'metaDesc'     => 'Temukan dan filter ratusan destinasi wisata di Bogor berdasarkan kategori, harga, dan rating.',
            'destinations' => $destinations,
            'categories'   => $categories,
            'wishlistIds'  => $wishlistIds,
            'search'       => $search,
            'catSlug'      => $catSlug,
            'sort'         => $sort,
        ]);
    }

    /**
     * GET /kuliner
     * Menampilkan katalog khusus kuliner khas Bogor.
     */
    public function kuliner(): void {
        $db             = Database::getInstance()->getConnection();
        $categoryModel  = new Category();

        $search   = trim($_GET['q']    ?? '');
        $sort     = trim($_GET['sort'] ?? 'terbaru');

        $where  = ["d.category_id = 3"];
        $params = [];

        if ($search !== '') {
            $where[]  = "(d.name LIKE :q1 OR d.address LIKE :q2 OR d.description LIKE :q3)";
            $params[':q1'] = "%{$search}%";
            $params[':q2'] = "%{$search}%";
            $params[':q3'] = "%{$search}%";
        }

        $whereClause = 'WHERE ' . implode(' AND ', $where);

        $orderMap = [
            'terbaru'    => 'd.id DESC',
            'terlama'    => 'd.id ASC',
            'rating'     => 'avg_rating DESC',
            'harga_asc'  => 'd.ticket_price ASC',
            'harga_desc' => 'd.ticket_price DESC',
            'nama'       => 'd.name ASC',
        ];
        $orderBy = $orderMap[$sort] ?? 'd.id DESC';

        $sql = "SELECT d.*, c.name AS category_name,
                       (SELECT image_path FROM destination_images
                        WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                       COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                                 WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating,
                       (SELECT COUNT(*) FROM reviews
                        WHERE destination_id = d.id AND is_visible = 1) AS review_count
                FROM destinations d
                LEFT JOIN categories c ON d.category_id = c.id
                {$whereClause}
                ORDER BY {$orderBy}";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = $categoryModel->getCategoriesWithCount();

        $wishlistIds = [];
        if (isset($_SESSION['user_id'])) {
            $wStmt = $db->prepare("SELECT destination_id FROM wishlists WHERE user_id = ?");
            $wStmt->execute([$_SESSION['user_id']]);
            $wishlistIds = array_column($wStmt->fetchAll(PDO::FETCH_ASSOC), 'destination_id');
        }

        $this->view('destinations/catalog', [
            'title'        => 'Wisata Kuliner Bogor — Tempat Makan Khas & Legendaris',
            'metaDesc'     => 'Jelajahi hidangan lezat, tempat makan legendaris, dan restoran khas Sunda terbaik di Bogor.',
            'destinations' => $destinations,
            'categories'   => $categories,
            'wishlistIds'  => $wishlistIds,
            'search'       => $search,
            'catSlug'      => 'kuliner',
            'sort'         => $sort,
            'isKulinerPage'=> true
        ]);
    }

    /**
     * GET /destinations/{slug}
     * Menampilkan halaman detail satu destinasi.
     */
    public function detail(string $slug): void {
        $db               = Database::getInstance()->getConnection();
        $destinationModel = new Destination();
        $reviewModel      = new Review();

        // Ambil data destinasi
        $destination = $destinationModel->findBySlugWithCategory($slug);
        if (!$destination) {
            http_response_code(404);
            die('<h2 style="text-align:center;padding:4rem;">Destinasi tidak ditemukan.</h2>');
        }

        // Galeri foto
        $imageStmt = $db->prepare(
            "SELECT * FROM destination_images WHERE destination_id = ? ORDER BY is_primary DESC, id ASC"
        );
        $imageStmt->execute([$destination['id']]);
        $images = $imageStmt->fetchAll(PDO::FETCH_ASSOC);

        // Ulasan yang tersetujui
        $reviews = $reviewModel->getVisibleReviewsByDestination((int)$destination['id']);

        // Rata-rata rating
        $avgRating = 0;
        if (!empty($reviews)) {
            $avgRating = round(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
        }

        // Apakah destinasi ada di wishlist user
        $isWishlisted = false;
        if (isset($_SESSION['user_id'])) {
            $wStmt = $db->prepare(
                "SELECT id FROM wishlists WHERE user_id = ? AND destination_id = ?"
            );
            $wStmt->execute([$_SESSION['user_id'], $destination['id']]);
            $isWishlisted = (bool)$wStmt->fetchColumn();
        }

        // Data ulasan milik user yang sedang login (jika ada)
        $userReview  = null;
        $hasReviewed = false;
        if (isset($_SESSION['user_id'])) {
            $userReview  = $reviewModel->getUserReviewForDestination((int)$_SESSION['user_id'], (int)$destination['id']);
            $hasReviewed = !empty($userReview);
        }

        // Flash message dari ReviewController
        $flashMsg = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        // Destinasi terkait (same category, beda slug)
        $relatedStmt = $db->prepare(
            "SELECT d.*, (SELECT image_path FROM destination_images
                          WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM destinations d
             WHERE d.category_id = ? AND d.slug != ?
             ORDER BY RAND()
             LIMIT 3"
        );
        $relatedStmt->execute([$destination['category_id'], $slug]);
        $related = $relatedStmt->fetchAll(PDO::FETCH_ASSOC);

        $weather = getLiveWeather($destination['latitude'], $destination['longitude']);

        $linkModel   = new DestinationLink();
        $ticketLink  = $linkModel->findActiveByDestinationId((int)$destination['id']);

        // Hotel terdekat
        $hotelModel    = new Hotel();
        $nearbyHotels  = $hotelModel->getByDestination((int)$destination['id']);

        $this->view('destinations/detail', [
            'title'        => $destination['name'] . ' — Wisata Bogor',
            'metaDesc'     => substr(strip_tags($destination['description'] ?? ''), 0, 155),
            'destination'  => $destination,
            'images'       => $images,
            'reviews'      => $reviews,
            'userReview'   => $userReview,
            'avgRating'    => $avgRating,
            'isWishlisted' => $isWishlisted,
            'hasReviewed'  => $hasReviewed,
            'related'      => $related,
            'flashMsg'     => $flashMsg,
            'weather'      => $weather,
            'ticketLink'   => $ticketLink,
            'nearbyHotels' => $nearbyHotels,
        ]);
    }
}
