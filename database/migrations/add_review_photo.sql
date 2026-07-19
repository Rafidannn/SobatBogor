-- database/migrations/add_review_photo.sql
-- SobatBogor - Tambah kolom photo_path pada tabel reviews
-- Run: mysql -u root sobatbogor < add_review_photo.sql

USE sobatbogor;

ALTER TABLE reviews
    ADD COLUMN photo_path VARCHAR(300) NULL DEFAULT NULL AFTER comment;
