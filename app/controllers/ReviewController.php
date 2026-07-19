<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/Review.php';
require_once ROOT_PATH . '/app/models/Destination.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';

/**
 * ReviewController.php
 * Menangani pengiriman ulasan dari halaman detail destinasi.
 * Ulasan yang dikirim default is_visible = 0 dan harus disetujui admin.
 */
class ReviewController extends Controller {

    /**
     * POST /reviews/submit
     * Menyimpan ulasan baru dari user yang sudah login.
     */
    public function submit(): void {
        AuthMiddleware::handle();

        $destinationId = (int)($_POST['destination_id'] ?? 0);
        $rating        = (int)($_POST['rating']         ?? 0);
        $comment       = trim($_POST['comment']         ?? '');
        $userId        = (int) $_SESSION['user_id'];

        // Validasi input
        if ($destinationId <= 0 || $rating < 1 || $rating > 5) {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Rating tidak valid. Harap pilih 1–5 bintang.',
            ];
            $this->redirectToDestination($destinationId);
        }

        $db = Database::getInstance()->getConnection();

        // Cek apakah sudah pernah review
        $checkStmt = $db->prepare(
            "SELECT id FROM reviews WHERE user_id = ? AND destination_id = ?"
        );
        $checkStmt->execute([$userId, $destinationId]);
        if ($checkStmt->fetchColumn()) {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Kamu sudah pernah memberikan ulasan untuk destinasi ini.',
            ];
            $this->redirectToDestination($destinationId);
        }

        // Simpan ulasan (is_visible = 0, menunggu persetujuan admin)
        $reviewModel = new Review();
        $result = $reviewModel->create([
            'user_id'        => $userId,
            'destination_id' => $destinationId,
            'rating'         => $rating,
            'comment'        => $comment,
            'is_visible'     => 0,
        ]);

        if ($result) {
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Ulasan berhasil dikirim dan menunggu persetujuan admin. Terima kasih!',
            ];
        } else {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Gagal mengirim ulasan. Silakan coba lagi.',
            ];
        }

        $this->redirectToDestination($destinationId);
    }

    /**
     * Helper: redirect ke halaman detail destinasi berdasarkan ID
     */
    private function redirectToDestination(int $destinationId): void {
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT slug FROM destinations WHERE id = ? LIMIT 1");
        $stmt->execute([$destinationId]);
        $slug = $stmt->fetchColumn();

        if ($slug) {
            $this->redirect('/destinations/' . $slug);
        } else {
            $this->redirect('/destinations');
        }
    }
}
