-- Migration: Tabel nearby_hotels
-- Jalankan di phpMyAdmin atau MySQL CLI

CREATE TABLE IF NOT EXISTS `nearby_hotels` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `destination_id` INT UNSIGNED   NOT NULL,
    `name`          VARCHAR(200)    NOT NULL,
    `star_rating`   TINYINT         NOT NULL DEFAULT 3,
    `price_start`   DECIMAL(10,2)   NOT NULL DEFAULT 0,
    `distance_text` VARCHAR(100)    DEFAULT NULL COMMENT 'Contoh: 1.2 km dari lokasi',
    `image_path`    VARCHAR(300)    DEFAULT NULL,
    `traveloka_url` TEXT            DEFAULT NULL,
    `booking_url`   TEXT            DEFAULT NULL,
    `is_active`     TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DUMMY DATA: 8 Hotel untuk 4 Destinasi
-- =============================================

-- Kebun Raya Bogor (destination_id = 1)
INSERT INTO `nearby_hotels` (`destination_id`, `name`, `star_rating`, `price_start`, `distance_text`, `image_path`, `traveloka_url`, `booking_url`) VALUES
(1, 'Novotel Bogor Golf Resort', 4, 750000, '0.9 km dari lokasi',  'assets/uploads/hotels/novotel-bogor/main.webp',        'https://www.traveloka.com/id-id/hotel/indonesia/novotel-bogor-golf-resort-and-convention-center-3000010009611',    'https://www.booking.com/hotel/id/novotel-bogor-golf-resort-and-convention-center.id.html'),
(1, 'Aston Bogor Hotel & Resort', 4, 650000, '1.3 km dari lokasi', 'assets/uploads/hotels/aston-bogor/main.jpg',           'https://www.traveloka.com/id-id/hotel/indonesia/aston-bogor-hotel-and-resort-3000010003801',                        'https://www.booking.com/hotel/id/aston-bogor.id.html'),
(1, 'Swiss-Belhotel Bogor',       4, 550000, '1.8 km dari lokasi', 'assets/uploads/hotels/swiss-belhotel-bogor/main.jpg',  'https://www.traveloka.com/id-id/hotel/indonesia/swiss-belhotel-bogor-3000010006551',                                'https://www.booking.com/hotel/id/swiss-belhotel-bogor.id.html');

-- Taman Safari Indonesia (destination_id = 2)
INSERT INTO `nearby_hotels` (`destination_id`, `name`, `star_rating`, `price_start`, `distance_text`, `image_path`, `traveloka_url`, `booking_url`) VALUES
(2, 'The Highland Park Resort',   4, 900000, '0.5 km dari lokasi', 'assets/uploads/hotels/highland-park-resort/main.webp', 'https://www.traveloka.com/id-id/hotel/indonesia/the-highland-park-resort-3000010016351',                             'https://www.booking.com/hotel/id/the-highland-park-resort-bogor.id.html'),
(2, 'Puncak Pass Resort',         3, 450000, '2.1 km dari lokasi', 'assets/uploads/hotels/puncak-pass-resort/main.webp',   'https://www.traveloka.com/id-id/hotel/indonesia/puncak-pass-resort-3000010010201',                                    'https://www.booking.com/hotel/id/puncak-pass-resort.id.html'),
(2, 'Taman Safari Lodge',         3, 1500000,'Di dalam kompleks',  'assets/uploads/hotels/taman-safari-lodge/main.jpg',    'https://www.traveloka.com/id-id/hotel/indonesia/taman-safari-lodge-3000010023151',                                    'https://www.booking.com/hotel/id/taman-safari-lodge.id.html');

-- Kuntum Farmfield (destination_id = 3)
INSERT INTO `nearby_hotels` (`destination_id`, `name`, `star_rating`, `price_start`, `distance_text`, `image_path`, `traveloka_url`, `booking_url`) VALUES
(3, 'The Highland Park Resort',   4, 900000, '3.2 km dari lokasi', 'assets/uploads/hotels/highland-park-resort/main.webp', 'https://www.traveloka.com/id-id/hotel/indonesia/the-highland-park-resort-3000010016351',                             'https://www.booking.com/hotel/id/the-highland-park-resort-bogor.id.html'),
(3, 'Lembah Dewata Resort',       3, 500000, '1.4 km dari lokasi', 'assets/uploads/hotels/lembah-dewata-resort/main.webp', 'https://www.traveloka.com/id-id/hotel/indonesia/lembah-dewata-resort-3000010029801',                                 'https://www.booking.com/hotel/id/lembah-dewata-resort.id.html'),
(3, 'Bukit Indah Puncak',         3, 400000, '2.0 km dari lokasi', 'assets/uploads/hotels/bukit-indah-puncak/main.webp',  'https://www.traveloka.com/id-id/hotel/indonesia/bukit-indah-puncak-hotel-3000010031201',                              'https://www.booking.com/hotel/id/bukit-indah-puncak.id.html');

-- Cimory Riverside (destination_id = 5)
INSERT INTO `nearby_hotels` (`destination_id`, `name`, `star_rating`, `price_start`, `distance_text`, `image_path`, `traveloka_url`, `booking_url`) VALUES
(5, 'Puncak Pass Resort',         3, 450000, '3.5 km dari lokasi', 'assets/uploads/hotels/puncak-pass-resort/main.webp',  'https://www.traveloka.com/id-id/hotel/indonesia/puncak-pass-resort-3000010010201',                                    'https://www.booking.com/hotel/id/puncak-pass-resort.id.html'),
(5, 'Lembah Dewata Resort',       3, 500000, '2.8 km dari lokasi', 'assets/uploads/hotels/lembah-dewata-resort/main.webp','https://www.traveloka.com/id-id/hotel/indonesia/lembah-dewata-resort-3000010029801',                                  'https://www.booking.com/hotel/id/lembah-dewata-resort.id.html'),
(5, 'Bukit Indah Puncak',         3, 400000, '1.2 km dari lokasi', 'assets/uploads/hotels/bukit-indah-puncak/main.webp',  'https://www.traveloka.com/id-id/hotel/indonesia/bukit-indah-puncak-hotel-3000010031201',                             'https://www.booking.com/hotel/id/bukit-indah-puncak.id.html');
