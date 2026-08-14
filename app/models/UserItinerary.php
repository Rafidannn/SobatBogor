<?php
/**
 * Model: UserItinerary.php
 * Maps to table: user_itineraries (id, user_id, title, created_at, updated_at)
 */
class UserItinerary extends Model {
    protected $table = 'user_itineraries';

    /**
     * Buat itinerary baru, kembalikan ID yang baru dibuat.
     */
    public function createForUser(int $userId, string $title): int {
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (user_id, title) VALUES (?, ?)"
        );
        $stmt->execute([$userId, $title]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Ambil semua itinerary milik user beserta jumlah destinasi.
     */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT ui.*,
                    COUNT(uii.id) AS total_items,
                    COUNT(DISTINCT uii.day_number) AS total_days
             FROM {$this->table} ui
             LEFT JOIN user_itinerary_items uii ON uii.itinerary_id = ui.id
             WHERE ui.user_id = ?
             GROUP BY ui.id
             ORDER BY ui.updated_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil itinerary beserta semua item (+ detail destinasi), validasi kepemilikan.
     * Kembalikan null jika tidak ditemukan / bukan milik user.
     */
    public function getWithItems(int $id, int $userId): ?array {
        // Ambil header itinerary
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$id, $userId]);
        $itinerary = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$itinerary) return null;

        // Ambil items dengan detail destinasi
        $stmt = $this->db->prepare(
            "SELECT uii.id AS item_id, uii.day_number, uii.sort_order,
                    d.id AS destination_id, d.name, d.slug, d.address,
                    d.latitude, d.longitude,
                    d.ticket_price, d.ticket_price_weekday,
                    c.name AS category_name, c.icon AS category_icon,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM user_itinerary_items uii
             JOIN destinations d ON d.id = uii.destination_id
             LEFT JOIN categories c ON c.id = d.category_id
             WHERE uii.itinerary_id = ?
             ORDER BY uii.day_number ASC, uii.sort_order ASC"
        );
        $stmt->execute([$id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Kelompokkan per hari
        $days = [];
        foreach ($items as $item) {
            $days[$item['day_number']][] = $item;
        }
        ksort($days);

        $itinerary['items']     = $items;
        $itinerary['days']      = $days;
        $itinerary['max_day']   = $days ? max(array_keys($days)) : 1;
        return $itinerary;
    }

    /**
     * Hapus itinerary milik user (items dihapus otomatis via CASCADE).
     */
    public function deleteById(int $id, int $userId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$id, $userId]);
    }

    /**
     * Update judul itinerary.
     */
    public function updateTitle(int $id, int $userId, string $title): bool {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET title = ? WHERE id = ? AND user_id = ?"
        );
        return $stmt->execute([$title, $id, $userId]);
    }

    /**
     * Sentuh updated_at agar urutan list terbaru di atas.
     */
    public function touchUpdatedAt(int $id): void {
        $this->db->prepare(
            "UPDATE {$this->table} SET updated_at = NOW() WHERE id = ?"
        )->execute([$id]);
    }

    /**
     * Validasi kepemilikan saja (tanpa join items).
     */
    public function ownsItinerary(int $id, int $userId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM {$this->table} WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $stmt->execute([$id, $userId]);
        return (bool) $stmt->fetchColumn();
    }
}
