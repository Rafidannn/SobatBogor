<?php
// Model: User.php
// Maps to table: users (id, name, email, password, role, provider, provider_id)
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
     * Mencari user berdasarkan OAuth provider dan provider_id.
     * Digunakan saat callback OAuth (Google / Facebook).
     */
    public function findByProvider(string $provider, string $providerId): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE provider = ? AND provider_id = ? LIMIT 1"
        );
        $stmt->execute([$provider, $providerId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Cari atau buat user berdasarkan data dari OAuth provider.
     * Jika email sudah ada → hubungkan akun. Jika belum → buat baru.
     *
     * @param array $oauthData  ['provider', 'provider_id', 'name', 'email', 'avatar']
     * @return array  User row dari database
     */
    public function findOrCreateOAuthUser(array $oauthData): array {
        $provider   = $oauthData['provider'];
        $providerId = $oauthData['provider_id'];
        $name       = $oauthData['name']   ?? 'Pengguna';
        $email      = $oauthData['email']  ?? null;

        // 1) Cari berdasarkan provider + provider_id
        $user = $this->findByProvider($provider, $providerId);
        if ($user) return $user;

        // 2) Cari berdasarkan email (akun lokal yang sudah ada)
        if ($email) {
            $user = $this->findByEmail($email);
            if ($user) {
                // Hubungkan akun lama dengan OAuth provider
                $this->db->prepare(
                    "UPDATE users SET provider = ?, provider_id = ? WHERE id = ?"
                )->execute([$provider, $providerId, $user['id']]);
                return $this->findById((int)$user['id']);
            }
        }

        // 3) Buat akun baru
        $this->create([
            'name'        => $name,
            'email'       => $email ?? ($provider . '_' . $providerId . '@oauth.local'),
            'password'    => null,
            'role'        => 'user',
            'provider'    => $provider,
            'provider_id' => $providerId,
        ]);
        return $this->findById((int)$this->lastInsertId());
    }

    /**
     * Mendapatkan ID terakhir yang baru saja di-INSERT.
     * Digunakan setelah Register berhasil.
     */
    public function lastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}
