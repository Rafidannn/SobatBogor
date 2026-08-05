-- database/migrations/add_oauth_fields.sql
-- Tambah kolom OAuth provider ke tabel users

ALTER TABLE users
    ADD COLUMN provider    VARCHAR(20)  NULL    AFTER role,
    ADD COLUMN provider_id VARCHAR(255) NULL    AFTER provider,
    MODIFY COLUMN password VARCHAR(255) NULL COMMENT 'NULL jika user login via OAuth (tidak punya password lokal)';
