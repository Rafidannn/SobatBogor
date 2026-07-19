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
}
