<?php
/**
 * Model: UserItineraryItem.php
 * Maps to table: user_itinerary_items
 * (id, itinerary_id, destination_id, day_number, sort_order, created_at)
 */
class UserItineraryItem extends Model {
    protected $table = 'user_itinerary_items';

    /**
     * Ganti seluruh items itinerary dalam 1 transaksi.
     * $items = [ ['destination_id' => X, 'day_number' => Y, 'sort_order' => Z], ... ]
     *
     * Validasi destination_id dilakukan di controller sebelum memanggil ini.
     */
    public function replaceItems(int $itineraryId, array $items): bool {
        try {
            $this->db->beginTransaction();

            // Hapus semua item lama
            $del = $this->db->prepare("DELETE FROM {$this->table} WHERE itinerary_id = ?");
            $del->execute([$itineraryId]);

            // Insert batch item baru
            if (!empty($items)) {
                $ins = $this->db->prepare(
                    "INSERT INTO {$this->table} (itinerary_id, destination_id, day_number, sort_order)
                     VALUES (?, ?, ?, ?)"
                );
                foreach ($items as $item) {
                    $ins->execute([
                        $itineraryId,
                        (int) $item['destination_id'],
                        (int) $item['day_number'],
                        (int) $item['sort_order'],
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Ambil semua items sebuah itinerary beserta detail destinasi,
     * diurutkan per hari lalu sort_order.
     */
    public function getByItinerary(int $itineraryId): array {
        $stmt = $this->db->prepare(
            "SELECT uii.*,
                    d.name, d.slug, d.address, d.latitude, d.longitude,
                    d.ticket_price, d.ticket_price_weekday,
                    c.name AS category_name, c.icon AS category_icon,
                    (SELECT image_path FROM destination_images
                     WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image
             FROM {$this->table} uii
             JOIN destinations d ON d.id = uii.destination_id
             LEFT JOIN categories c ON c.id = d.category_id
             WHERE uii.itinerary_id = ?
             ORDER BY uii.day_number ASC, uii.sort_order ASC"
        );
        $stmt->execute([$itineraryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah destination_id valid (ada di tabel destinations).
     */
    public static function isValidDestination(PDO $db, int $destId): bool {
        $stmt = $db->prepare("SELECT id FROM destinations WHERE id = ? LIMIT 1");
        $stmt->execute([$destId]);
        return (bool) $stmt->fetchColumn();
    }
}
