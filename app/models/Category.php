<?php
// Model: Category.php
// Maps to table: categories (id, name, slug, icon)
class Category extends Model {
    protected $table = 'categories';

    /**
     * Mencari kategori berdasarkan slug
     */
    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Memeriksa apakah slug kategori sudah digunakan
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
     * Mengambil kategori beserta jumlah destinasi di dalamnya
     */
    public function getCategoriesWithCount(): array {
        $query = "SELECT c.*, COUNT(d.id) AS total_destinations 
                  FROM {$this->table} c 
                  LEFT JOIN destinations d ON c.id = d.category_id 
                  GROUP BY c.id 
                  ORDER BY c.name ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
