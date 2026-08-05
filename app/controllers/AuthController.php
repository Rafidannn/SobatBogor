<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/OAuthClient.php';
require_once ROOT_PATH . '/app/models/User.php';

/**
 * AuthController.php
 * Menangani semua proses autentikasi:
 * - showLogin()             : Menampilkan form login
 * - login()                 : Memproses form login (POST), validasi & set session
 * - showRegister()          : Menampilkan form registrasi
 * - register()              : Memproses form register (POST), validasi & simpan ke DB
 * - logout()                : Menghapus session & redirect ke beranda
 * - redirectToGoogle()      : Redirect ke Google OAuth consent screen
 * - handleGoogleCallback()  : Proses callback dari Google
 * - redirectToFacebook()    : Redirect ke Facebook OAuth consent screen
 * - handleFacebookCallback(): Proses callback dari Facebook
 */
class AuthController extends Controller {

    private User $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->userModel = new User();
    }

    // ── Tampilkan halaman Login ──────────────────────────────────────
    public function showLogin(): void {
        // Jika sudah login, redirect langsung ke beranda
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/login', ['title' => 'Login - SobatBogor'], 'none');
    }

    // ── Proses form Login (POST) ────────────────────────────────────
    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input tidak kosong
        if (empty($email) || empty($password)) {
            $this->view('auth/login', [
                'title'      => 'Login - SobatBogor',
                'error'      => 'Email dan password wajib diisi.',
                'errorField' => 'both',
            ], 'none');
            return;
        }

        // Cari user berdasarkan email
        $user = $this->userModel->findByEmail($email);

        // Email tidak ditemukan di database
        if (!$user) {
            $this->view('auth/login', [
                'title'      => 'Login - SobatBogor',
                'error'      => 'Email tidak terdaftar. Coba cek kembali atau daftar akun baru.',
                'errorField' => 'email',
            ], 'none');
            return;
        }

        // Akun ini adalah akun OAuth, tidak punya password lokal
        if (empty($user['password'])) {
            $provider = ucfirst($user['provider'] ?? 'sosial');
            $this->view('auth/login', [
                'title'      => 'Login - SobatBogor',
                'error'      => "Akun ini terdaftar via {$provider}. Silakan gunakan tombol \"Masuk dengan {$provider}\".",
                'errorField' => 'email',
            ], 'none');
            return;
        }

        // Password tidak cocok
        if (!password_verify($password, $user['password'])) {
            $this->view('auth/login', [
                'title'      => 'Login - SobatBogor',
                'error'      => 'Password yang kamu masukkan salah. Silakan coba lagi.',
                'errorField' => 'password',
            ], 'none');
            return;
        }

        // Set session setelah login berhasil
        $this->setUserSession($user);

        // Redirect ke URL sebelumnya jika ada, atau ke beranda
        $redirect = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);
        $this->redirect($redirect);
    }

    // ── Tampilkan halaman Register ───────────────────────────────────
    public function showRegister(): void {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor'], 'none');
    }

    // ── Proses form Register (POST) ─────────────────────────────────
    public function register(): void {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor', 'error' => 'Semua kolom wajib diisi.'], 'none');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor', 'error' => 'Format email tidak valid.'], 'none');
            return;
        }
        if (strlen($password) < 6) {
            $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor', 'error' => 'Password minimal 6 karakter.'], 'none');
            return;
        }
        if ($password !== $confirm) {
            $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor', 'error' => 'Konfirmasi password tidak cocok.'], 'none');
            return;
        }
        if ($this->userModel->findByEmail($email)) {
            $this->view('auth/register', ['title' => 'Daftar Akun - SobatBogor', 'error' => 'Email sudah terdaftar. Gunakan email lain atau login.'], 'none');
            return;
        }

        $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => 'user',
        ]);

        $newUser = $this->userModel->findByEmail($email);
        $this->setUserSession($newUser);
        $this->redirect('/');
    }

    // ── Logout ──────────────────────────────────────────────────────
    public function logout(): void {
        session_destroy();
        $this->redirect('/');
    }

    // ════════════════════════════════════════════════════════════════
    // ── GOOGLE OAUTH ────────────────────────────────────────────────
    // ════════════════════════════════════════════════════════════════

    /**
     * Redirect ke Google consent screen.
     * Route: GET /auth/google
     */
    public function redirectToGoogle(): void {
        $cfg = require ROOT_PATH . '/config/app.php';
        $googleCfg = $cfg['google_oauth'];

        if (empty($googleCfg['client_id'])) {
            $this->view('auth/login', [
                'title' => 'Login - SobatBogor',
                'error' => 'Google Login belum dikonfigurasi. Silakan gunakan email & password.',
            ], 'none');
            return;
        }

        $url = OAuthClient::buildGoogleAuthUrl($googleCfg);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Proses callback dari Google.
     * Route: GET /auth/google/callback
     */
    public function handleGoogleCallback(): void {
        $code  = $_GET['code'] ?? null;
        $error = $_GET['error'] ?? null;

        if ($error || !$code) {
            $this->redirect('/login');
            return;
        }

        $cfg       = require ROOT_PATH . '/config/app.php';
        $googleCfg = $cfg['google_oauth'];

        // Tukar code dengan token
        $tokenData = OAuthClient::getGoogleToken($googleCfg, $code);
        if (!$tokenData || empty($tokenData['access_token'])) {
            $this->view('auth/login', ['title' => 'Login - SobatBogor', 'error' => 'Gagal mendapatkan token dari Google. Coba lagi.'], 'none');
            return;
        }

        // Ambil data user dari Google
        $oauthUser = OAuthClient::getGoogleUser($tokenData['access_token']);
        if (!$oauthUser || empty($oauthUser['provider_id'])) {
            $this->view('auth/login', ['title' => 'Login - SobatBogor', 'error' => 'Gagal mengambil data profil dari Google.'], 'none');
            return;
        }

        // Cari atau buat user di database
        $user = $this->userModel->findOrCreateOAuthUser($oauthUser);
        $this->setUserSession($user);

        $redirect = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);
        $this->redirect($redirect);
    }

    // ════════════════════════════════════════════════════════════════
    // ── FACEBOOK OAUTH ──────────────────────────────────────────────
    // ════════════════════════════════════════════════════════════════

    /**
     * Redirect ke Facebook consent screen.
     * Route: GET /auth/facebook
     */
    public function redirectToFacebook(): void {
        $cfg         = require ROOT_PATH . '/config/app.php';
        $facebookCfg = $cfg['facebook_oauth'];

        if (empty($facebookCfg['app_id'])) {
            $this->view('auth/login', [
                'title' => 'Login - SobatBogor',
                'error' => 'Facebook Login belum dikonfigurasi. Silakan gunakan email & password.',
            ], 'none');
            return;
        }

        $url = OAuthClient::buildFacebookAuthUrl($facebookCfg);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Proses callback dari Facebook.
     * Route: GET /auth/facebook/callback
     */
    public function handleFacebookCallback(): void {
        $code  = $_GET['code'] ?? null;
        $error = $_GET['error'] ?? null;

        if ($error || !$code) {
            $this->redirect('/login');
            return;
        }

        $cfg         = require ROOT_PATH . '/config/app.php';
        $facebookCfg = $cfg['facebook_oauth'];

        // Tukar code dengan token
        $tokenData = OAuthClient::getFacebookToken($facebookCfg, $code);
        if (!$tokenData || empty($tokenData['access_token'])) {
            $this->view('auth/login', ['title' => 'Login - SobatBogor', 'error' => 'Gagal mendapatkan token dari Facebook. Coba lagi.'], 'none');
            return;
        }

        // Ambil data user dari Facebook
        $oauthUser = OAuthClient::getFacebookUser($tokenData['access_token']);
        if (!$oauthUser || empty($oauthUser['provider_id'])) {
            $this->view('auth/login', ['title' => 'Login - SobatBogor', 'error' => 'Gagal mengambil data profil dari Facebook.'], 'none');
            return;
        }

        // Cari atau buat user di database
        $user = $this->userModel->findOrCreateOAuthUser($oauthUser);
        $this->setUserSession($user);

        $redirect = $_SESSION['redirect_after_login'] ?? '/';
        unset($_SESSION['redirect_after_login']);
        $this->redirect($redirect);
    }

    // ── Private Helper ───────────────────────────────────────────────

    /**
     * Set session data setelah login berhasil (lokal maupun OAuth).
     */
    private function setUserSession(array $user): void {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];
    }
}


