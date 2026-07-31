<?php
// Model: Review.php
// Maps to table: reviews (id, user_id, destination_id, rating, comment, is_visible)
class Review extends Model {
    protected $table = 'reviews';

    /**
     * Mengambil ulasan beserta nama user dan nama destinasi (untuk admin)
     */
    public function findAllWithRelations(): array {
        $query = "SELECT r.*, u.name AS user_name, u.email AS user_email, d.name AS destination_name 
                  FROM {$this->table} r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  LEFT JOIN destinations d ON r.destination_id = d.id 
                  ORDER BY r.id DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mendapatkan ulasan untuk destinasi tertentu yang statusnya kelihatan (is_visible = 1)
     */
    public function getVisibleReviewsByDestination(int $destinationId): array {
        $query = "SELECT r.*, u.name AS user_name 
                  FROM {$this->table} r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  WHERE r.destination_id = ? AND r.is_visible = 1 
                  ORDER BY r.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$destinationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil semua ulasan yang dibuat oleh user tertentu beserta detail destinasi
     */
    public function getByUserId(int $userId): array {
        $query = "SELECT r.*, d.name AS destination_name, d.slug AS destination_slug,
                         (SELECT image_path FROM destination_images 
                          WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS destination_image
                  FROM {$this->table} r
                  JOIN destinations d ON r.destination_id = d.id
                  WHERE r.user_id = ?
                  ORDER BY r.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mencari ulasan spesifik milik user tertentu (pengecekan hak akses)
     */
    public function findByIdAndUser(int $id, int $userId): ?array {
        $query = "SELECT r.*, d.name AS destination_name, d.slug AS destination_slug
                  FROM {$this->table} r
                  JOIN destinations d ON r.destination_id = d.id
                  WHERE r.id = ? AND r.user_id = ?
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Mendapatkan ulasan milik user untuk destinasi tertentu (baik visible maupun pending)
     */
    public function getUserReviewForDestination(int $userId, int $destinationId): ?array {
        $query = "SELECT r.* FROM {$this->table} r
                  WHERE r.user_id = ? AND r.destination_id = ?
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $destinationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}

