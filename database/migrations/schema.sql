-- database/migrations/schema.sql
-- SobatBogor - Full Database Schema
-- TODO: Run this in Tugas 1

CREATE DATABASE IF NOT EXISTS sobatbogor CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sobatbogor;

-- Drop tables in order to avoid foreign key constraint errors
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS wishlists;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS destination_images;
DROP TABLE IF EXISTS destinations;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: categories
CREATE TABLE IF NOT EXISTS categories (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    slug       VARCHAR(120) NOT NULL UNIQUE,
    icon       VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: destinations
CREATE TABLE IF NOT EXISTS destinations (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id  INT UNSIGNED NOT NULL,
    name         VARCHAR(200) NOT NULL,
    slug         VARCHAR(220) NOT NULL UNIQUE,
    description  TEXT,
    address      VARCHAR(300),
    latitude     DECIMAL(10, 8),
    longitude    DECIMAL(11, 8),
    ticket_price DECIMAL(10, 2) DEFAULT 0,
    open_hours   VARCHAR(100),
    is_featured  TINYINT(1) DEFAULT 0,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table: destination_images
CREATE TABLE IF NOT EXISTS destination_images (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    destination_id INT UNSIGNED NOT NULL,
    image_path     VARCHAR(300) NOT NULL,
    is_primary     TINYINT(1) DEFAULT 0,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
);

-- Table: reviews
CREATE TABLE IF NOT EXISTS reviews (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED NOT NULL,
    destination_id INT UNSIGNED NOT NULL,
    rating         TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment        TEXT,
    is_visible     TINYINT(1) DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
);

-- Table: wishlists
CREATE TABLE IF NOT EXISTS wishlists (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id        INT UNSIGNED NOT NULL,
    destination_id INT UNSIGNED NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist (user_id, destination_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
);
