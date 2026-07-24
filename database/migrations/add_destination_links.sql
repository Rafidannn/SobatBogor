CREATE TABLE IF NOT EXISTS `destination_links` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
