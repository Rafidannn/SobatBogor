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
     * GET /my-reviews
     * Menampilkan daftar semua ulasan milik user yang sedang login.
     */
    public function myReviews(): void {
        AuthMiddleware::handle();

        $userId      = (int)$_SESSION['user_id'];
        $reviewModel = new Review();
        $reviews     = $reviewModel->getByUserId($userId);

        $flashMsg = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->view('reviews/my_reviews', [
            'title'    => 'Ulasan Saya — SobatBogor',
            'reviews'  => $reviews,
            'flashMsg' => $flashMsg,
        ]);
    }

    /**
     * POST /reviews/submit
     * Menyimpan ulasan baru dari user yang sudah login.
     * Mendukung upload foto opsional (jpg/jpeg/png/webp, maks 3MB).
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
                'message' => 'Kamu sudah pernah memberikan ulasan untuk destinasi ini. Silakan ubah ulasan yang sudah ada.',
            ];
            $this->redirectToDestination($destinationId);
        }

        // Upload Foto Ulasan (opsional)
        $photoPath = $this->handlePhotoUpload($userId, $destinationId);

        // Simpan ulasan (is_visible = 0, menunggu persetujuan admin)
        $reviewModel = new Review();
        $result = $reviewModel->create([
            'user_id'        => $userId,
            'destination_id' => $destinationId,
            'rating'         => $rating,
            'comment'        => $comment,
            'photo_path'     => $photoPath,
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
     * POST /reviews/update/{id}
     * Memperbarui ulasan yang milik user yang sedang login.
     */
    public function update(int $id): void {
        AuthMiddleware::handle();

        $userId      = (int)$_SESSION['user_id'];
        $reviewModel = new Review();
        $review      = $reviewModel->findByIdAndUser($id, $userId);

        $redirectTo = $_POST['redirect_to'] ?? '';

        if (!$review) {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Ulasan tidak ditemukan atau kamu tidak memiliki hak akses.',
            ];
            $this->smartRedirect($redirectTo, $review['destination_id'] ?? 0);
        }

        $rating  = (int)($_POST['rating']  ?? 0);
        $comment = trim($_POST['comment']  ?? '');

        if ($rating < 1 || $rating > 5) {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Rating tidak valid. Pilih 1–5 bintang.',
            ];
            $this->smartRedirect($redirectTo, (int)$review['destination_id']);
        }

        $photoPath = $review['photo_path'];

        // Jika user menghapus foto
        if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
            if ($photoPath && file_exists(ROOT_PATH . '/public/' . $photoPath)) {
                @unlink(ROOT_PATH . '/public/' . $photoPath);
            }
            $photoPath = null;
        }

        // Jika upload foto baru
        if (!empty($_FILES['review_photo']['name'])) {
            $newPhoto = $this->handlePhotoUpload($userId, (int)$review['destination_id']);
            if ($newPhoto) {
                if ($photoPath && file_exists(ROOT_PATH . '/public/' . $photoPath)) {
                    @unlink(ROOT_PATH . '/public/' . $photoPath);
                }
                $photoPath = $newPhoto;
            }
        }

        // Update ulasan dan reset is_visible = 0 agar dire-moderasi admin
        $updated = $reviewModel->update($id, [
            'rating'     => $rating,
            'comment'    => $comment,
            'photo_path' => $photoPath,
            'is_visible' => 0,
        ]);

        if ($updated) {
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Ulasan berhasil diperbarui dan dikirim kembali untuk peninjauan admin.',
            ];
        } else {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Gagal memperbarui ulasan. Coba lagi.',
            ];
        }

        $this->smartRedirect($redirectTo, (int)$review['destination_id']);
    }

    /**
     * POST /reviews/delete/{id}
     * Menghapus ulasan milik user yang sedang login.
     */
    public function delete(int $id): void {
        AuthMiddleware::handle();

        $userId      = (int)$_SESSION['user_id'];
        $reviewModel = new Review();
        $review      = $reviewModel->findByIdAndUser($id, $userId);

        $redirectTo = $_POST['redirect_to'] ?? '';

        if (!$review) {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Ulasan tidak ditemukan atau kamu tidak berhak menghapusnya.',
            ];
            $this->smartRedirect($redirectTo, 0);
        }

        // Hapus foto jika ada
        if (!empty($review['photo_path']) && file_exists(ROOT_PATH . '/public/' . $review['photo_path'])) {
            @unlink(ROOT_PATH . '/public/' . $review['photo_path']);
        }

        // Hapus dari DB
        $deleted = $reviewModel->delete($id);

        if ($deleted) {
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Ulasan berhasil dihapus.',
            ];
        } else {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Gagal menghapus ulasan. Silakan coba lagi.',
            ];
        }

        $this->smartRedirect($redirectTo, (int)$review['destination_id']);
    }

    /**
     * Helper upload foto ulasan
     */
    private function handlePhotoUpload(int $userId, int $destinationId): ?string {
        if (empty($_FILES['review_photo']['name'])) {
            return null;
        }

        $file     = $_FILES['review_photo'];
        $allowed  = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize  = 3 * 1024 * 1024; // 3 MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowed) || $file['size'] > $maxSize) {
            return null;
        }

        $uploadDir = ROOT_PATH . '/public/assets/uploads/reviews/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'review_' . $userId . '_' . $destinationId . '_' . time() . '.' . strtolower($ext);

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return 'assets/uploads/reviews/' . $filename;
        }

        return null;
    }

    /**
     * Helper redirect cerdas (halaman saya vs detail destinasi)
     */
    private function smartRedirect(string $redirectTo, int $destinationId): void {
        if ($redirectTo === 'my_reviews') {
            $this->redirect('/my-reviews');
        } elseif ($destinationId > 0) {
            $this->redirectToDestination($destinationId);
        } else {
            $this->redirect('/my-reviews');
        }
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

