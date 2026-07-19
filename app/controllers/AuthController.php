<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/app/models/User.php';

/**
 * AuthController.php
 * Menangani semua proses autentikasi:
 * - showLogin()   : Menampilkan form login
 * - login()       : Memproses form login (POST), validasi & set session
 * - showRegister(): Menampilkan form registrasi
 * - register()    : Memproses form register (POST), validasi & simpan ke DB
 * - logout()      : Menghapus session & redirect ke beranda
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
                'title' => 'Login - SobatBogor',
                'error' => 'Email dan password wajib diisi.'
            ]);
            return;
        }

        // Cari user berdasarkan email
        $user = $this->userModel->findByEmail($email);

        // Verifikasi user ditemukan & password cocok
        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('auth/login', [
                'title' => 'Login - SobatBogor',
                'error' => 'Email atau password salah.'
            ]);
            return;
        }

        // Set session setelah login berhasil
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'];

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

        // Validasi tidak boleh kosong
        if (empty($name) || empty($email) || empty($password)) {
            $this->view('auth/register', [
                'title' => 'Daftar Akun - SobatBogor',
                'error' => 'Semua kolom wajib diisi.'
            ]);
            return;
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', [
                'title' => 'Daftar Akun - SobatBogor',
                'error' => 'Format email tidak valid.'
            ]);
            return;
        }

        // Validasi password minimal 6 karakter
        if (strlen($password) < 6) {
            $this->view('auth/register', [
                'title' => 'Daftar Akun - SobatBogor',
                'error' => 'Password minimal 6 karakter.'
            ]);
            return;
        }

        // Validasi konfirmasi password
        if ($password !== $confirm) {
            $this->view('auth/register', [
                'title' => 'Daftar Akun - SobatBogor',
                'error' => 'Konfirmasi password tidak cocok.'
            ]);
            return;
        }

        // Cek email sudah terdaftar atau belum
        if ($this->userModel->findByEmail($email)) {
            $this->view('auth/register', [
                'title' => 'Daftar Akun - SobatBogor',
                'error' => 'Email sudah terdaftar. Gunakan email lain atau login.'
            ]);
            return;
        }

        // Hash password sebelum disimpan ke database
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Simpan user baru ke database
        $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => 'user',
        ]);

        // Set session langsung setelah register berhasil
        $newUserId = $this->userModel->lastInsertId();
        $_SESSION['user_id']   = $newUserId;
        $_SESSION['user_name'] = $name;
        $_SESSION['role']      = 'user';

        $this->redirect('/');
    }

    // ── Logout ──────────────────────────────────────────────────────
    public function logout(): void {
        session_destroy();
        $this->redirect('/');
    }
}
