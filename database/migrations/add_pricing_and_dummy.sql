-- database/migrations/add_pricing_and_dummy.sql
-- SobatBogor - Penambahan Kolom Harga Weekday & Weekend + Data Dummy Terkenal

USE sobatbogor;

-- 1. Tambahkan kolom ticket_price_weekday & ticket_price_weekend jika belum ada
ALTER TABLE destinations 
    ADD COLUMN ticket_price_weekday DECIMAL(10, 2) DEFAULT 0.00 AFTER ticket_price,
    ADD COLUMN ticket_price_weekend DECIMAL(10, 2) DEFAULT 0.00 AFTER ticket_price_weekday;

-- Update data Kebun Raya Bogor yang sudah ada
UPDATE destinations SET 
    ticket_price = 15500.00,
    ticket_price_weekday = 15500.00,
    ticket_price_weekend = 25500.00,
    open_hours = '07:00 - 16:00 WIB'
WHERE id = 1;

-- 2. Masukkan Destinasi Wisata Terkenal Bogor Lainnya
INSERT INTO destinations (id, category_id, name, slug, description, address, latitude, longitude, ticket_price, ticket_price_weekday, ticket_price_weekend, open_hours, is_featured) VALUES
(2, 
 4, 
 'Taman Safari Indonesia', 
 'taman-safari-indonesia', 
 'Taman Safari Indonesia Bogor adalah tempat wisata keluarga berwawasan lingkungan yang berorientasi pada habitat satwa di alam bebas. Pengunjung dapat berkeliling menggunakan mobil pribadi atau bus safari untuk melihat secara langsung aneka satwa langka dari seluruh dunia.', 
 'Jl. Kapten Harun Kabir No.724, Cibeureum, Kec. Cisarua, Kabupaten Bogor, Jawa Barat 16750', 
 -6.72080000, 
 106.95310000, 
 230000.00, 
 230000.00, 
 255000.00, 
 '08:30 - 17:00 WIB', 
 1),

(3, 
 4, 
 'Kuntum Farmfield', 
 'kuntum-farmfield', 
 'Kuntum Farmfield adalah destinasi agrowisata edukasi keluarga berbasis peternakan dan pertanian organik. Pengunjung dan anak-anak dapat langsung memberi makan domba, kelinci, sapi, serta belajar bercocok tanam di area yang asri.', 
 'Jl. Raya Tajur No.291, Sindangrasa, Kec. Bogor Timur, Kota Bogor, Jawa Barat 16145', 
 -6.63450000, 
 106.83780000, 
 70000.00, 
 70000.00, 
 80000.00, 
 '08:00 - 18:00 WIB', 
 1),

(4, 
 2, 
 'Devoyage Bogor', 
 'devoyage-bogor', 
 'Devoyage Bogor menyajikan konsep taman wisata bergaya Eropa dengan miniatur bangunan terkenal seperti Menara Eiffel, Kincir Angin Belanda, dan kanal mendayung perahu gondola. Sangat cocok untuk spot foto instagramable.', 
 'Jl. Boulevard Bogor Nirwana Residence, Mulyaharja, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16135', 
 -6.62120000, 
 106.79340000, 
 30000.00, 
 30000.00, 
 40000.00, 
 '09:00 - 18:00 WIB', 
 1),

(5, 
 3, 
 'Cimory Riverside', 
 'cimory-riverside', 
 'Cimory Riverside adalah restoran dan taman rekreasi keluarga yang terletak di tepi Sungai Ciliwung kawasan Puncak. Menyajikan beragam hidangan lezat, produk olahan susu segar, serta wahana wisata menyusuri sungai dan hutan.', 
 'Jl. Raya Puncak - Gadog No.KM.77, Leuwimalang, Kec. Cisarua, Kabupaten Bogor, Jawa Barat 16750', 
 -6.67120000, 
 106.88560000, 
 20000.00, 
 20000.00, 
 25000.00, 
 '08:00 - 21:00 WIB', 
 1)
ON DUPLICATE KEY UPDATE 
    ticket_price_weekday = VALUES(ticket_price_weekday),
    ticket_price_weekend = VALUES(ticket_price_weekend);

-- 3. Masukkan Gambar Pendukung untuk Destinasi Baru
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(2, 'assets/uploads/kebun_raya_bogor_1.jpg', 1),
(3, 'assets/uploads/kebun_raya_bogor_2.jpg', 1),
(4, 'assets/uploads/kebun_raya_bogor_1.jpg', 1),
(5, 'assets/uploads/kebun_raya_bogor_2.jpg', 1);
