<?php
/**
 * app/models/Hotel.php
 * Model for nearby_hotels table
 */
class Hotel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /** Get all active hotels for a specific destination */
    public function getByDestination(int $destinationId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM nearby_hotels
             WHERE destination_id = ? AND is_active = 1
             ORDER BY star_rating DESC, price_start ASC"
        );
        $stmt->execute([$destinationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Get filtered active hotels for public catalog (deduplicated by hotel name) */
    public function getFilteredPublic(string $search = '', int $star = 0, float $maxPrice = 0): array
    {
        $where  = ["h.is_active = 1", "h.id IN (SELECT MIN(id) FROM nearby_hotels WHERE is_active = 1 GROUP BY name)"];
        $params = [];

        if ($search !== '') {
            $where[] = "(h.name LIKE :q OR h.address LIKE :q OR d.name LIKE :q)";
            $params[':q'] = "%{$search}%";
        }
        if ($star > 0) {
            $where[] = "h.star_rating = :star";
            $params[':star'] = $star;
        }
        if ($maxPrice > 0) {
            $where[] = "h.price_start <= :max_price";
            $params[':max_price'] = $maxPrice;
        }

        $whereSql = implode(' AND ', $where);
        $sql = "SELECT h.*, d.name AS destination_name, d.slug AS destination_slug
                FROM nearby_hotels h
                JOIN destinations d ON d.id = h.destination_id
                WHERE {$whereSql}
                ORDER BY h.star_rating DESC, h.price_start ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Get all hotels (admin) */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT h.*, d.name AS destination_name
             FROM nearby_hotels h
             JOIN destinations d ON d.id = h.destination_id
             ORDER BY d.name, h.star_rating DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Get single hotel by ID */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM nearby_hotels WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Get single hotel by ID with destination info (public) */
    public function findByIdWithDestination(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT h.*, d.name AS destination_name, d.slug AS destination_slug, d.address AS destination_address
             FROM nearby_hotels h
             JOIN destinations d ON d.id = h.destination_id
             WHERE h.id = ?"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Insert new hotel */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO nearby_hotels
             (destination_id, name, star_rating, price_start, distance_text, latitude, longitude, address, description, facilities, image_path, traveloka_url, booking_url, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['destination_id'],
            $data['name'],
            $data['star_rating'],
            $data['price_start'],
            $data['distance_text'] ?? null,
            $data['latitude']      ?? null,
            $data['longitude']     ?? null,
            $data['address']       ?? null,
            $data['description']   ?? null,
            $data['facilities']    ?? null,
            $data['image_path']    ?? null,
            $data['traveloka_url'] ?? null,
            $data['booking_url']   ?? null,
            $data['is_active']     ?? 1,
        ]);
    }

    /** Update hotel */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE nearby_hotels SET
             destination_id = ?, name = ?, star_rating = ?, price_start = ?,
             distance_text = ?, latitude = ?, longitude = ?, address = ?, description = ?, facilities = ?,
             image_path = ?, traveloka_url = ?, booking_url = ?, is_active = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['destination_id'],
            $data['name'],
            $data['star_rating'],
            $data['price_start'],
            $data['distance_text'] ?? null,
            $data['latitude']      ?? null,
            $data['longitude']     ?? null,
            $data['address']       ?? null,
            $data['description']   ?? null,
            $data['facilities']    ?? null,
            $data['image_path']    ?? null,
            $data['traveloka_url'] ?? null,
            $data['booking_url']   ?? null,
            $data['is_active']     ?? 1,
            $id,
        ]);
    }

    /** Delete hotel */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM nearby_hotels WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
