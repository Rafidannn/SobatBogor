<?php
require_once ROOT_PATH . '/app/models/Review.php';

class ReviewAdminController extends Controller {
    private Review $reviewModel;

    public function __construct() {
        AdminMiddleware::handle();
        $this->reviewModel = new Review();
    }

    // Tampilkan panel moderasi ulasan
    public function index(): void {
        $reviews = $this->reviewModel->findAllWithRelations();
        $this->view('admin/reviews/index', [
            'title' => 'Moderasi Ulasan Pengunjung',
            'reviews' => $reviews
        ], 'admin');
    }

    // Toggle Sembunyikan / Tampilkan ulasan
    public function hide(int $id): void {
        $review = $this->reviewModel->findById($id);
        
        if (!$review) {
            $_SESSION['error'] = 'Ulasan tidak ditemukan.';
            $this->redirect('/admin/reviews');
        }

        // Balik status is_visible (1 ke 0, 0 ke 1)
        $newStatus = ($review['is_visible'] == 1) ? 0 : 1;
        $success = $this->reviewModel->update($id, ['is_visible' => $newStatus]);

        if ($success) {
            $_SESSION['success'] = ($newStatus == 0) ? 'Ulasan berhasil disembunyikan.' : 'Ulasan berhasil ditampilkan kembali.';
        } else {
            $_SESSION['error'] = 'Gagal mengubah visibilitas ulasan.';
        }

        $this->redirect('/admin/reviews');
    }

    // Hapus ulasan secara permanen
    public function delete(int $id): void {
        $success = $this->reviewModel->delete($id);

        if ($success) {
            $_SESSION['success'] = 'Ulasan berhasil dihapus secara permanen.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus ulasan.';
        }

        $this->redirect('/admin/reviews');
    }
}

