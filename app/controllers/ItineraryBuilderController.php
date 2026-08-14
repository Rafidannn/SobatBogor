<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/UserItinerary.php';
require_once ROOT_PATH . '/app/models/UserItineraryItem.php';
require_once ROOT_PATH . '/app/models/Category.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';

/**
 * ItineraryBuilderController.php
 * Menangani fitur Itinerary Builder Manual interaktif dengan Drag-and-Drop
 * dan penyimpanan real-time AJAX.
 */
class ItineraryBuilderController extends Controller {

    /**
     * GET /itinerary/builder
     * Menampilkan daftar itinerary milik user yang sedang login.
     */
    public function index(): void {
        AuthMiddleware::handle();

        $userId        = (int) $_SESSION['user_id'];
        $itineraryModel = new UserItinerary();
        $itineraries   = $itineraryModel->getByUser($userId);

        $this->view('itinerary/builder-list', [
            'title'       => 'Itinerary Saya',
            'metaDesc'    => 'Kelola dan atur daftar rencana liburan impianmu ke Bogor secara interaktif.',
            'itineraries' => $itineraries,
        ]);
    }

    /**
     * GET /itinerary/builder/create
     * Menampilkan form atau langsung membuat itinerary baru dengan judul default.
     */
    public function create(): void {
        AuthMiddleware::handle();

        $this->view('itinerary/builder-create', [
            'title'    => 'Buat Itinerary Baru',
            'metaDesc' => 'Buat itinerary baru dan atur jadwal liburanmu sendiri.',
        ]);
    }

    /**
     * POST /itinerary/builder/store
     * Menyimpan itinerary baru dan redirect ke halaman editor builder.
     */
    public function store(): void {
        AuthMiddleware::handle();

        $userId = (int) $_SESSION['user_id'];
        $title  = trim($_POST['title'] ?? '');

        if (empty($title)) {
            $title = 'Itinerary Liburan Bogor';
        }

        // Limit panjang title
        if (mb_strlen($title) > 150) {
            $title = mb_substr($title, 0, 150);
        }

        $itineraryModel = new UserItinerary();
        $newId          = $itineraryModel->createForUser($userId, $title);

        $this->redirect('/itinerary/builder/' . $newId);
    }

    /**
     * GET /itinerary/builder/{id}
     * Halaman Editor Drag-and-Drop Itinerary Builder.
     */
    public function edit(string $idStr): void {
        AuthMiddleware::handle();

        $id     = (int) $idStr;
        $userId = (int) $_SESSION['user_id'];

        if ($id <= 0) {
            $this->redirect('/itinerary/builder');
        }

        $itineraryModel = new UserItinerary();
        $itinerary      = $itineraryModel->getWithItems($id, $userId);

        if (!$itinerary) {
            // Itinerary tidak ada atau bukan milik user
            $this->redirect('/itinerary/builder');
        }

        // Ambil semua destinasi untuk sidebar pencarian
        $db = Database::getInstance()->getConnection();
        $destStmt = $db->query(
            "SELECT d.id, d.name, d.slug, d.address, d.ticket_price, d.ticket_price_weekday,
                    c.id AS category_id, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                    COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                              WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating
             FROM destinations d
             LEFT JOIN categories c ON d.category_id = c.id
             ORDER BY d.name ASC"
        );
        $allDestinations = $destStmt->fetchAll(PDO::FETCH_ASSOC);

        $categoryModel = new Category();
        $categories    = $categoryModel->getCategoriesWithCount();

        $this->view('itinerary/builder', [
            'title'           => 'Edit ' . htmlspecialchars($itinerary['title']),
            'metaDesc'        => 'Susun destinasi wisata Bogor pilihanmu dengan drag and drop.',
            'itinerary'       => $itinerary,
            'allDestinations' => $allDestinations,
            'categories'      => $categories,
        ]);
    }

    /**
     * POST /itinerary/builder/{id}/save
     * Endpoint AJAX untuk auto-save perubahan susunan dan judul itinerary.
     */
    public function saveState(string $idStr): void {
        AuthMiddleware::handle();

        $id     = (int) $idStr;
        $userId = (int) $_SESSION['user_id'];

        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'ID Itinerary tidak valid.'], 400);
        }

        $itineraryModel = new UserItinerary();
        if (!$itineraryModel->ownsItinerary($id, $userId)) {
            $this->json(['success' => false, 'message' => 'Akses ditolak atau itinerary tidak ditemukan.'], 403);
        }

        // Ambil data JSON dari body request atau $_POST
        $rawInput = file_get_contents('php://input');
        $data     = json_decode($rawInput, true);

        if (!is_array($data)) {
            $data = $_POST;
        }

        $title = trim($data['title'] ?? '');
        if (!empty($title)) {
            if (mb_strlen($title) > 150) {
                $title = mb_substr($title, 0, 150);
            }
            $itineraryModel->updateTitle($id, $userId, $title);
        }

        $rawItems = $data['items'] ?? [];
        $validItems = [];
        $db = Database::getInstance()->getConnection();

        if (is_array($rawItems)) {
            foreach ($rawItems as $item) {
                $destId    = (int) ($item['destination_id'] ?? 0);
                $dayNum    = (int) ($item['day_number'] ?? 1);
                $sortOrder = (int) ($item['sort_order'] ?? 0);

                if ($destId > 0 && $dayNum > 0 && UserItineraryItem::isValidDestination($db, $destId)) {
                    $validItems[] = [
                        'destination_id' => $destId,
                        'day_number'     => $dayNum,
                        'sort_order'     => $sortOrder,
                    ];
                }
            }
        }

        $itemModel = new UserItineraryItem();
        $saved     = $itemModel->replaceItems($id, $validItems);

        if ($saved) {
            $itineraryModel->touchUpdatedAt($id);
            $this->json([
                'success' => true,
                'message' => 'Itinerary berhasil disimpan.',
                'updated_at' => date('H:i:s')
            ]);
        } else {
            $this->json([
                'success' => false,
                'message' => 'Gagal menyimpan perubahan itinerary.'
            ], 500);
        }
    }

    /**
     * POST /itinerary/builder/{id}/delete
     * Menghapus itinerary milik user.
     */
    public function delete(string $idStr): void {
        AuthMiddleware::handle();

        $id     = (int) $idStr;
        $userId = (int) $_SESSION['user_id'];

        if ($id <= 0) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'ID tidak valid.'], 400);
            }
            $this->redirect('/itinerary/builder');
        }

        $itineraryModel = new UserItinerary();
        $deleted        = $itineraryModel->deleteById($id, $userId);

        if ($this->isAjax()) {
            $this->json([
                'success' => $deleted,
                'message' => $deleted ? 'Itinerary berhasil dihapus.' : 'Gagal menghapus itinerary.'
            ]);
        } else {
            $this->redirect('/itinerary/builder');
        }
    }

    private function isAjax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
