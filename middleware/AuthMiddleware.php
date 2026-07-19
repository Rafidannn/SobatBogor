<?php
/**
 * middleware/AuthMiddleware.php
 * Memastikan user sudah login. Jika belum, redirect ke halaman /login.
 * Dipanggil di awal setiap controller yang membutuhkan autentikasi (Wishlist, Review, dll.)
 */
class AuthMiddleware {
    public static function handle(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // Simpan URL tujuan agar setelah login bisa redirect balik
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
