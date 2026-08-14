-- Migration: Tambah 4 Destinasi Baru dan Foto-Fotonya

INSERT INTO destinations (id, category_id, name, slug, description, address, latitude, longitude, ticket_price, ticket_price_weekday, ticket_price_weekend, open_hours, is_featured) VALUES
(6, 4, 'Taman Bunga Nusantara', 'taman-bunga-nusantara', 'Taman Bunga Nusantara adalah taman seluas 35 hektare di Cianjur, Jawa Barat. Tempat ini memiliki koleksi bunga dunia, udara sejuk, taman labirin, rumah kaca, dan wahana baru Keranjang Sultan.', 'Jl. Mariwati KM 7, Kawungluwuk, Sukaresmi, Cianjur, Jawa Barat.', -6.72775, 107.07941, 50000, 50000, 50000, '08:00 - 17:00', 1),
(7, 4, 'Bogor Aquagame', 'bogor-aquagame', 'Bogor Aquagame adalah wahana taman air terapung (inflatable waterlake) pertama di Indonesia yang berada di atas danau. Tempat rekreasi ini menyajikan berbagai rintangan seru yang menguji kekompakan, keseimbangan, serta kerja sama tim, sehingga sangat cocok untuk liburan keluarga maupun acara gathering.', 'Jalan Danau Bogor Raya No.33, RT.01/RW.07, Tanah Baru, Kecamatan Bogor Utara, Kota Bogor, Jawa Barat 16144', -6.59837, 106.83354, 120000, 120000, 155000, '09:00 - 17:00', 1),
(8, 4, 'Fun Offroad Hambalang Sentul Bogor by Go Explore', 'fun-offroad-hambalang-sentul', 'Fun Offroad Hambalang Sentul Bogor by Go Explore adalah paket wisata petualangan luar ruangan memacu adrenalin melintasi jalur menantang dan berlumpur di kawasan perbukitan Hambalang. Aktivitas ini menggunakan kendaraan jip 4x4 (seperti Jimny atau Land Rover) dan sudah termasuk fasilitas pengemudi, pemandu, dokumentasi, serta asuransi.', 'Taman Budaya Sentul, Jalan Siliwangi No.1, Sumur Batu, Kecamatan Babakan Madang, Kabupaten Bogor, Jawa Barat 16810', -6.57467, 106.88371, 2150000, 2150000, 2150000, '08:00 - 17:00', 1),
(9, 2, 'Taman Wisata Alam Gunung Pancar', 'gunung-pancar', 'Taman Wisata Alam Gunung Pancar adalah kawasan konservasi alam pegunungan yang terkenal dengan hamparan hutan pinus yang asri, sejuk, dan sangat instagramable. Tempat ini menjadi destinasi favorit untuk aktivitas luar ruangan seperti piknik keluarga, bersepeda gunung, trekking, camping, foto pre-wedding, hingga relaksasi di pemandian air panas alami.', 'Jalan Desa, Karang Tengah, Kecamatan Babakan Madang, Kabupaten Bogor, Jawa Barat 16810', -6.59103, 106.91136, 5000, 5000, 7500, '08:00 - 17:00', 1);

-- Insert Images untuk Taman Bunga Nusantara (id = 6)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(6, 'assets/uploads/destinations/taman-bunga-nusantara/main.webp', 1),
(6, 'assets/uploads/destinations/taman-bunga-nusantara/gallery_1.webp', 0),
(6, 'assets/uploads/destinations/taman-bunga-nusantara/gallery_2.webp', 0);

-- Insert Images untuk Bogor Aquagame (id = 7)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(7, 'assets/uploads/destinations/bogor-aquagame/main.webp', 1),
(7, 'assets/uploads/destinations/bogor-aquagame/gallery_1.webp', 0),
(7, 'assets/uploads/destinations/bogor-aquagame/gallery_2.webp', 0);

-- Insert Images untuk Fun Offroad (id = 8)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(8, 'assets/uploads/destinations/fun-offroad/main.webp', 1),
(8, 'assets/uploads/destinations/fun-offroad/gallery_1.webp', 0),
(8, 'assets/uploads/destinations/fun-offroad/gallery_2.webp', 0);

-- Insert Images untuk Gunung Pancar (id = 9)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(9, 'assets/uploads/destinations/gunung-pancar/main.jpeg', 1),
(9, 'assets/uploads/destinations/gunung-pancar/gallery_1.jpg', 0),
(9, 'assets/uploads/destinations/gunung-pancar/gallery_2.jpg', 0);
