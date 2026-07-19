<?php
// Model: Destination.php
// Maps to table: destinations (id, category_id, name, slug, description, address, latitude, longitude, ticket_price, open_hours, is_featured)
class Destination extends Model {
    protected $table = 'destinations';

    /**
     * Mengambil seluruh destinasi beserta nama kategori dan gambar utamanya
     */
    public function findAllWithCategory(): array {
        $query = "SELECT d.*, c.name AS category_name, 
                         (SELECT image_path FROM destination_images WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image
                  FROM {$this->table} d
                  LEFT JOIN categories c ON d.category_id = c.id
                  ORDER BY d.id DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil satu data destinasi beserta nama kategorinya
     */
    public function findBySlugWithCategory(string $slug): ?array {
        $query = "SELECT d.*, c.name AS category_name
                  FROM {$this->table} d
                  LEFT JOIN categories c ON d.category_id = c.id
                  WHERE d.slug = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Memeriksa keunikan slug destinasi
     */
    public function isSlugExists(string $slug, ?int $excludeId = null): bool {
        $query = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ?";
        $params = [$slug];

        if ($excludeId !== null) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Mendapatkan ID terakhir yang dimasukkan
     */
    public function lastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}
