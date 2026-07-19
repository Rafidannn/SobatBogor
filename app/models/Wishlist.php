<?php
/**
 * Model: Wishlist.php
 * Maps to table: wishlists (id, user_id, destination_id, created_at)
 */
class Wishlist extends Model {
    protected $table = 'wishlists';

    /**
     * Cek apakah destinasi sudah ada di wishlist user
     */
    public function exists(int $userId, int $destinationId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM {$this->table} WHERE user_id = ? AND destination_id = ? LIMIT 1"
        );
        $stmt->execute([$userId, $destinationId]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Tambah destinasi ke wishlist
     */
    public function add(int $userId, int $destinationId): bool {
        if ($this->exists($userId, $destinationId)) return true; // sudah ada
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_id, destination_id) VALUES (?, ?)"
        );
        return $stmt->execute([$userId, $destinationId]);
    }

    /**
     * Hapus destinasi dari wishlist
     */
    public function remove(int $userId, int $destinationId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE user_id = ? AND destination_id = ?"
        );
        return $stmt->execute([$userId, $destinationId]);
    }

    /**
     * Ambil semua wishlist milik user beserta detail destinasi
     */
    public function getByUserWithDetails(int $userId): array {
        $query = "SELECT w.*, d.name, d.slug, d.address, d.ticket_price, d.open_hours,
                         c.name AS category_name,
                         (SELECT image_path FROM destination_images
                          WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                         COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews
                                   WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating
                  FROM {$this->table} w
                  LEFT JOIN destinations d ON w.destination_id = d.id
                  LEFT JOIN categories c ON d.category_id = c.id
                  WHERE w.user_id = ?
                  ORDER BY w.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
