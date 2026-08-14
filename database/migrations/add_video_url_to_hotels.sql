-- Migration: Tambah video_url ke tabel nearby_hotels dan isi data awal YouTube

ALTER TABLE `nearby_hotels` ADD COLUMN `video_url` VARCHAR(500) DEFAULT NULL AFTER `booking_url`;

-- Seed Video URLs untuk masing-masing Hotel
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=ZtdzPyATk3E' WHERE `name` = 'Aston Bogor Hotel & Resort';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=ONiSnojYNOw' WHERE `name` = 'Bukit Indah Puncak';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=NHGAPaX7T-A' WHERE `name` = 'Lembah Dewata Resort';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=MIZYFih29D8' WHERE `name` = 'Novotel Bogor Golf Resort';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=t50jpg-kB8w' WHERE `name` = 'Puncak Pass Resort';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=TEpQiwQzQC0' WHERE `name` = 'Swiss-Belhotel Bogor';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=5pvYb8mCNDI' WHERE `name` = 'Taman Safari Lodge';
UPDATE `nearby_hotels` SET `video_url` = 'https://www.youtube.com/watch?v=BUp1V61k12I' WHERE `name` = 'The Highland Park Resort';
