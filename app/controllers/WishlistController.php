<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/Wishlist.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';

/**
 * WishlistController.php
 * Menangani halaman wishlist pengguna dan endpoint AJAX add/remove.
 * Semua method dilindungi oleh AuthMiddleware (harus login).
 */
class WishlistController extends Controller {

    /**
     * GET /wishlist
     * Halaman daftar wishlist milik user yang sedang login.
     */
    public function index(): void {
        AuthMiddleware::handle();

        $wishlistModel = new Wishlist();
        $userId        = (int) $_SESSION['user_id'];
        $items         = $wishlistModel->getByUserWithDetails($userId);

        $this->view('wishlist/index', [
            'title'   => 'Wishlist Saya',
            'metaDesc'=> 'Daftar destinasi wisata yang kamu simpan di SobatBogor.',
            'items'   => $items,
        ]);
    }

    /**
     * POST /wishlist/add  (AJAX / JSON response)
     * Menambahkan destinasi ke wishlist user.
     */
    public function add(): void {
        AuthMiddleware::handle();

        $destinationId = (int)($_POST['destination_id'] ?? 0);
        if ($destinationId <= 0) {
            $this->json(['success' => false, 'message' => 'ID destinasi tidak valid.'], 400);
        }

        $wishlistModel = new Wishlist();
        $userId        = (int) $_SESSION['user_id'];
        $result        = $wishlistModel->add($userId, $destinationId);

        $this->json([
            'success' => $result,
            'message' => $result ? 'Ditambahkan ke wishlist.' : 'Gagal menambahkan.',
        ]);
    }

    /**
     * POST /wishlist/remove  (AJAX / JSON response)
     * Menghapus destinasi dari wishlist user.
     */
    public function remove(): void {
        AuthMiddleware::handle();

        $destinationId = (int)($_POST['destination_id'] ?? 0);
        if ($destinationId <= 0) {
            $this->json(['success' => false, 'message' => 'ID destinasi tidak valid.'], 400);
        }

        $wishlistModel = new Wishlist();
        $userId        = (int) $_SESSION['user_id'];
        $result        = $wishlistModel->remove($userId, $destinationId);

        $this->json([
            'success' => $result,
            'message' => $result ? 'Dihapus dari wishlist.' : 'Gagal menghapus.',
        ]);
    }
}
