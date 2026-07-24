<?php
class DestinationLink extends Model {
    protected $table = 'destination_links';

    public function __construct() {
        parent::__construct();
        $this->db->exec("CREATE TABLE IF NOT EXISTS `destination_links` (
            `id`             INT(11)      NOT NULL AUTO_INCREMENT,
            `destination_id` INT(11)      NOT NULL,
            `label`          VARCHAR(100) NOT NULL DEFAULT 'Pesan Tiket',
            `url`            VARCHAR(500) NOT NULL,
            `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
            `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uq_destination_link` (`destination_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function findByDestinationId(int $destinationId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE destination_id = ? LIMIT 1");
        $stmt->execute([$destinationId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findActiveByDestinationId(int $destinationId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE destination_id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$destinationId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function saveForDestination(int $destinationId, string $label, string $url, int $isActive): bool {
        $existing = $this->findByDestinationId($destinationId);
        if ($existing) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET label = ?, url = ?, is_active = ? WHERE destination_id = ?"
            );
            return $stmt->execute([$label, $url, $isActive, $destinationId]);
        }
        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (destination_id, label, url, is_active) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$destinationId, $label, $url, $isActive]);
    }
}
