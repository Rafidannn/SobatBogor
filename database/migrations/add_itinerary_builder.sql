-- database/migrations/add_itinerary_builder.sql
-- SobatBogor — Itinerary Builder Manual
-- Jalankan setelah schema.sql dan migration lainnya sudah ada.

-- ── Tabel: user_itineraries ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_itineraries (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    title      VARCHAR(150) NOT NULL DEFAULT 'Itinerary Saya',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tabel: user_itinerary_items ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_itinerary_items (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    itinerary_id   INT UNSIGNED NOT NULL,
    destination_id INT UNSIGNED NOT NULL,
    day_number     TINYINT UNSIGNED NOT NULL DEFAULT 1,
    sort_order     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (itinerary_id)   REFERENCES user_itineraries(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
