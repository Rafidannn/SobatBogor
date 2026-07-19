<?php
/**
 * middleware/AdminMiddleware.php
 * Memastikan user yang login memiliki role 'admin'.
 * Dipanggil di semua controller panel Admin.
 * Jika bukan admin, tampilkan error 403 Forbidden.
 */
class AdminMiddleware {
    public static function handle(): void {
        // Pastikan user sudah login terlebih dahulu
        AuthMiddleware::handle();

        if (($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            die("
                <h1>403 - Akses Ditolak</h1>
                <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <a href='" . BASE_URL . "'>Kembali ke Beranda</a>
            ");
        }
    }
}
