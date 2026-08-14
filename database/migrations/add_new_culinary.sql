-- Migration: Tambah 5 Tempat Kuliner Baru dan Foto-Fotonya (dari uploads/kuliner)

INSERT INTO destinations (id, category_id, name, slug, description, address, latitude, longitude, ticket_price, ticket_price_weekday, ticket_price_weekend, open_hours, is_featured) VALUES
(10, 3, 'Soto Kuning PAK M YUSUP', 'soto-kuning-pak-m-yusup', 'Soto kuning khas Bogor dengan kuah kuning gurih dan pilihan daging serta jeroan seperti daging sapi, limpa, paru, dan kikil. Pembeli dapat memilih isian sendiri dan harga dihitung berdasarkan potongan yang dipilih.', 'Jl. Suryakencana No. 260 G, RT.01/RW.05, Gudang, Bogor Tengah, Kota Bogor, Jawa Barat 16142', -6.6097102, 106.8042787, 30000, 30000, 30000, '09:00 - 20:00', 1),
(11, 3, 'Soto Mie "Agih" Bogor', 'soto-mie-agih-bogor', 'Soto mie khas Bogor dengan kuah gurih dan isian mie, serta pilihan daging/jeroan. Tempat ini dikenal sebagai salah satu kuliner khas di kawasan Suryakencana.', 'Jl. Suryakencana No. 313, RT.01/RW.02, Babakan Pasar, Bogor Tengah, Kota Bogor, Jawa Barat 16126', -6.60739, 106.80250, 50000, 50000, 50000, '08:00 - 19:00', 1),
(12, 3, 'Mie Kocok Mawar', 'mie-kocok-mawar', 'Mie kocok khas Bogor dengan mie kuning, tauge, sawi, bakso, kikil kaki sapi, dan ayam kampung. Menu andalannya adalah Mie Kocok Komplit.', 'Jl. Dr. Sumeru No. 2, RT.003/RW.008, Kebon Kelapa, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16125', -6.59036, 106.78567, 30000, 30000, 30000, '12:00 - 21:00', 1),
(13, 3, 'Gurih 7 Bogor', 'gurih-7-bogor', 'Restoran khas Sunda dengan konsep saung dan suasana air terjun. Menu andalannya antara lain Ayam Bakakak Goreng, Gurame Goreng Kipas, Sate Kambing, dan Sop Iga Kedondong.', 'Jl. Raya Pajajaran No. 102, RT.03/RW.12, Bantarjati, Kecamatan Bogor Utara, Kota Bogor, Jawa Barat 16153', -6.58400, 106.81100, 50000, 50000, 50000, '09:00 - 21:30', 1),
(14, 3, 'Bumi Aki', 'bumi-aki-bogor', 'Restoran keluarga yang menyajikan masakan khas Sunda, masakan rumahan, dan hidangan Nusantara. Menu yang tersedia antara lain Nasi Timbel Komplit, Nasi Liwet Komplit, Ayam Goreng/Bakar Kampung, Karedok, dan Sate Ayam.', 'Jl. Raya Pajajaran No. 51, RT.04/RW.13, Bantarjati, Kecamatan Bogor Utara, Kota Bogor, Jawa Barat 16153', -6.58100, 106.81100, 75000, 75000, 75000, '07:00 - 22:00', 1);

-- Insert Images untuk Soto Kuning PAK M YUSUP (id = 10)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(10, 'assets/uploads/kuliner/soto-yusuf/main.png', 1),
(10, 'assets/uploads/kuliner/soto-yusuf/gallery_1.png', 0);

-- Insert Images untuk Soto Mie "Agih" Bogor (id = 11)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(11, 'assets/uploads/kuliner/soto-mie-agih/main.png', 1),
(11, 'assets/uploads/kuliner/soto-mie-agih/gallery_1.png', 0);

-- Insert Images untuk Mie Kocok Mawar (id = 12)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(12, 'assets/uploads/kuliner/mie-kocok-mawar/main.png', 1),
(12, 'assets/uploads/kuliner/mie-kocok-mawar/gallery_1.png', 0);

-- Insert Images untuk Gurih 7 Bogor (id = 13)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(13, 'assets/uploads/kuliner/gurih-7/main.png', 1),
(13, 'assets/uploads/kuliner/gurih-7/gallery_1.png', 0);

-- Insert Images untuk Bumi Aki (id = 14)
INSERT INTO destination_images (destination_id, image_path, is_primary) VALUES
(14, 'assets/uploads/kuliner/bumiaki/main.png', 1),
(14, 'assets/uploads/kuliner/bumiaki/gallery_1.png', 0);
