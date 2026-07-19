<?php
class AdminController extends Controller {

    public function __construct() {
        AdminMiddleware::handle();
    }

    public function dashboard(): void {
        $db = Database::getInstance()->getConnection();

        // Ambil hitungan statistik cepat
        $totalDestinations = $db->query("SELECT COUNT(*) FROM destinations")->fetchColumn();
        $totalCategories   = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        $totalReviews      = $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $totalUsers        = $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

        // Mengambil 5 ulasan terbaru
        $stmtReviews = $db->query("SELECT r.*, u.name AS user_name, d.name AS destination_name 
                                   FROM reviews r 
                                   LEFT JOIN users u ON r.user_id = u.id 
                                   LEFT JOIN destinations d ON r.destination_id = d.id 
                                   ORDER BY r.id DESC LIMIT 5");
        $latestReviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

        // Mengambil 5 destinasi terpopuler / terakhir ditambahkan
        $stmtDests = $db->query("SELECT d.*, c.name AS category_name 
                                 FROM destinations d 
                                 LEFT JOIN categories c ON d.category_id = c.id 
                                 ORDER BY d.id DESC LIMIT 5");
        $latestDestinations = $stmtDests->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => [
                'destinations' => $totalDestinations,
                'categories'   => $totalCategories,
                'reviews'      => $totalReviews,
                'users'        => $totalUsers
            ],
            'latestReviews'      => $latestReviews,
            'latestDestinations' => $latestDestinations
        ], 'admin');
    }
}
