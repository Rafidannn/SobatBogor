-- database/migrations/add_video_url_to_destinations.sql
-- Adds video_url column to destinations and seeds the 4 existing destinations

USE sobatbogor;

-- 1. Add video_url column if not exists
ALTER TABLE destinations ADD COLUMN video_url VARCHAR(500) DEFAULT NULL AFTER description;

-- 2. Update initial video URLs for existing destinations
UPDATE destinations SET video_url = 'https://www.youtube.com/watch?v=dvm6IKoEvYo' WHERE name = 'Kebun Raya Bogor';
UPDATE destinations SET video_url = 'https://www.youtube.com/watch?v=QCNQx5ItrN8' WHERE name = 'Taman Safari Indonesia';
UPDATE destinations SET video_url = 'https://www.youtube.com/watch?v=waWh1HjAfmQ' WHERE name = 'Kuntum Farmfield';
UPDATE destinations SET video_url = 'https://www.youtube.com/watch?v=H9r-C4zTYyQ' WHERE name = 'Cimory Riverside';
