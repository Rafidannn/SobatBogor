<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';

$db = Database::getInstance()->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `destination_links` (
    `id`             INT(11)      NOT NULL AUTO_INCREMENT,
    `destination_id` INT(11)      NOT NULL,
    `label`          VARCHAR(100) NOT NULL DEFAULT 'Pesan Tiket',
    `url`            VARCHAR(500) NOT NULL,
    `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_destination_link` (`destination_id`),
    CONSTRAINT `fk_dest_link_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

try {
    $db->exec($sql);
    echo '<p style="font-family:sans-serif;color:green;padding:2rem;">✅ Tabel <b>destination_links</b> berhasil dibuat! Silakan hapus file ini setelah selesai.</p>';
} catch (PDOException $e) {
    echo '<p style="font-family:sans-serif;color:red;padding:2rem;">❌ Error: ' . $e->getMessage() . '</p>';
}
