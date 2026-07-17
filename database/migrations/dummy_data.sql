-- database/migrations/dummy_data.sql
-- SobatBogor - High Quality Dummy Data
-- Run this after running schema.sql

USE sobatbogor;

-- 1. Insert Users (Password: password123, dihashing menggunakan bcrypt '$2y$10$tM9sE7Pmsi1jAfe4gVfX5.z.YQJ2z9U2V7xZ3H3vU2b5a5c5d5e5f')
-- Role: Admin dan Registered User
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Administrator SobatBogor', 'admin@sobatbogor.com', '$2y$10$tM9sE7Pmsi1jAfe4gVfX5.z.YQJ2z9U2V7xZ3H3vU2b5a5c5d5e5f', 'admin'),
(2, 'Budi Setiawan', 'budi@gmail.com', '$2y$10$tM9sE7Pmsi1jAfe4gVfX5.z.YQJ2z9U2V7xZ3H3vU2b5a5c5d5e5f', 'user');

-- 2. Insert Categories
INSERT INTO categories (id, name, slug, icon) VALUES
(1, 'Sejarah & Edukasi', 'sejarah-dan-edukasi', 'fa-landmark'),
(2, 'Wisata Alam', 'wisata-alam', 'fa-tree'),
(3, 'Kuliner', 'kuliner', 'fa-utensils'),
(4, 'Keluarga & Rekreasi', 'keluarga-dan-rekreasi', 'fa-umbrella-beach');

-- 3. Insert Destination (Kebun Raya Bogor)
INSERT INTO destinations (id, category_id, name, slug, description, address, latitude, longitude, ticket_price, open_hours, is_featured) VALUES
(1, 
 1, 
 'Kebun Raya Bogor', 
 'kebun-raya-bogor', 
 'Kebun Raya Bogor adalah kebun botani besar yang terletak di Kota Bogor, Indonesia. Luasnya mencapai 87 hektare dan memiliki 15.000 jenis koleksi pohon dan tumbuhan. Tempat ini sangat cocok untuk wisata edukasi keluarga, piknik santai di bawah rindangnya pepohonan, serta mempelajari sejarah botani di Indonesia.', 
 'Jl. Ir. H. Juanda No.13, Paledang, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16122', 
 -6.59810000, 
 106.79940000, 
 25500.00, 
 '08:00 - 16:00 WIB', 
 1);

-- 4. Insert Destination Images
INSERT INTO destination_images (id, destination_id, image_path, is_primary) VALUES
(1, 1, 'assets/uploads/kebun_raya_bogor_1.jpg', 1),
(2, 1, 'assets/uploads/kebun_raya_bogor_2.jpg', 0);

-- 5. Insert Review
INSERT INTO reviews (id, user_id, destination_id, rating, comment, is_visible) VALUES
(1, 2, 1, 5, 'Kebun Raya Bogor sangat indah dan sejuk! Cocok sekali untuk piknik akhir pekan bersama keluarga. Tempatnya bersih dan terawat dengan baik.', 1);

-- 6. Insert Wishlist
INSERT INTO wishlists (id, user_id, destination_id) VALUES
(1, 2, 1);
