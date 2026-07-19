<?php
// Model: User.php
// Maps to table: users (id, name, email, password, role)
class User extends Model {
    protected $table = 'users';

    /**
     * Mencari user berdasarkan email.
     * Digunakan saat Login (verifikasi email) dan Register (cek email sudah ada).
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Mendapatkan ID terakhir yang baru saja di-INSERT.
     * Digunakan setelah Register berhasil.
     */
    public function lastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}
