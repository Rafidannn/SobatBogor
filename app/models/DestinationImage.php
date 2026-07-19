<?php
// Model: DestinationImage.php
// Maps to table: destination_images (id, destination_id, image_path, is_primary)
class DestinationImage extends Model {
    protected $table = 'destination_images';

    /**
     * Mendapatkan semua gambar untuk destinasi tertentu
     */
    public function getImagesByDestination(int $destinationId): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE destination_id = ? ORDER BY is_primary DESC, id ASC");
        $stmt->execute([$destinationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengatur ulang semua gambar destinasi agar tidak menjadi gambar utama (is_primary = 0)
     * Digunakan sebelum menetapkan gambar utama baru
     */
    public function resetPrimary(int $destinationId): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_primary = 0 WHERE destination_id = ?");
        return $stmt->execute([$destinationId]);
    }
}
