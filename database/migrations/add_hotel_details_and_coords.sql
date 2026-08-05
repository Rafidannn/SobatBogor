-- Migration: Add columns to nearby_hotels for GPS coordinates, address, description, and facilities
USE sobatbogor;

ALTER TABLE `nearby_hotels`
ADD COLUMN `latitude`    DECIMAL(10,8) DEFAULT NULL AFTER `distance_text`,
ADD COLUMN `longitude`   DECIMAL(11,8) DEFAULT NULL AFTER `latitude`,
ADD COLUMN `address`     VARCHAR(300)  DEFAULT NULL AFTER `longitude`,
ADD COLUMN `description` TEXT          DEFAULT NULL AFTER `address`,
ADD COLUMN `facilities`  TEXT          DEFAULT NULL AFTER `description`;

-- Update data hotel 1: Novotel Bogor Golf Resort
UPDATE `nearby_hotels` SET
  `latitude` = -6.61111620,
  `longitude` = 106.82855750,
  `address` = 'Jl. Golf Estate Bogor Raya, Sukaraja, Kabupaten Bogor, Jawa Barat 16710',
  `description` = 'Novotel Bogor Golf Resort & Convention Center terletak di kawasan Bogor Raya yang asri. Dikelilingi taman tropis dan lapangan golf 18-hole, hotel bintang 4 ini menawarkan suasana tenang nan mewah.',
  `facilities` = 'Kolam Renang, WiFi Gratis, Lapangan Golf, Restoran, Spa, AC, Parkir Gratis, Pusat Kebugaran'
WHERE `id` = 1;

-- Update data hotel 2: Aston Bogor Hotel & Resort
UPDATE `nearby_hotels` SET
  `latitude` = -6.62772500,
  `longitude` = 106.79758500,
  `address` = 'Jl. Nirwana Residence, Mulyaharja, Bogor Selatan, Kota Bogor, Jawa Barat 16135',
  `description` = 'Aston Bogor Hotel & Resort merupakan hotel resort bernuansa alam dengan latar pemandangan Gunung Salak. Dilengkapi dengan kolam renang outdoor dan fasilitas keluarga lengkap.',
  `facilities` = 'Kolam Renang Outdoor, WiFi Gratis, Restoran, Spa, Taman Bermain Anak, AC, Bar'
WHERE `id` = 2;

-- Update data hotel 3: Swiss-Belhotel Bogor
UPDATE `nearby_hotels` SET
  `latitude` = -6.58661200,
  `longitude` = 106.80492800,
  `address` = 'Jl. Salak No.38-40, Babakan, Kec. Bogor Tengah, Kota Bogor, Jawa Barat 16128',
  `description` = 'Swiss-Belhotel Bogor berlokasi strategis di pusat Kota Bogor, dekat dengan Kebun Raya Bogor dan pusat kuliner. Pilihan tepat untuk perjalanan bisnis maupun liburan keluarga.',
  `facilities` = 'Kolam Renang Renang Rooftop, WiFi Gratis, Restoran, Ruang Rapat, AC, Pusat Kebugaran'
WHERE `id` = 3;

-- Update data hotel 4: The Highland Park Resort
UPDATE `nearby_hotels` SET
  `latitude` = -6.66698900,
  `longitude` = 106.74567200,
  `address` = 'Jl. Curug Nangka, Sinarwangi, Sukajadi, Tamansari, Kabupaten Bogor, Jawa Barat 16610',
  `description` = 'The Highland Park Resort Bogor menawarkan konsep unik Glamping (Glamour Camping) ala tenda Apache dan Mongolia dengan pemandangan langsung Gunung Salak.',
  `facilities` = 'Tenda Glamping, Kolam Renang Waterpark, Outbound, WiFi Gratis, Restoran, Lapangan Futsal'
WHERE `id` = 4;

-- Update data hotel 5: Puncak Pass Resort
UPDATE `nearby_hotels` SET
  `latitude` = -6.69854200,
  `longitude` = 106.98394400,
  `address` = 'Jl. Raya Puncak KM. 90, Tugu Selatan, Cisarua, Kabupaten Bogor, Jawa Barat 16750',
  `description` = 'Puncak Pass Resort adalah tempat menginap legendaris di puncak bukit Cisarua. Dikelilingi perkebunan teh yang hijau dengan udara sejuk nan menyegarkan.',
  `facilities` = 'Kolam Renang, Pemandangan Perkebunan Teh, Restoran Legendaris, Spa, WiFi Gratis, Area Outbound'
WHERE `id` = 5;

-- Update data hotel 6: Taman Safari Lodge
UPDATE `nearby_hotels` SET
  `latitude` = -6.71442100,
  `longitude` = 106.94589200,
  `address` = 'Jl. Kapten Harun Kabir No.724, Cibeureum, Cisarua, Kabupaten Bogor, Jawa Barat 16750',
  `description` = 'Safari Lodge berada langsung di dalam kawasan wisata Taman Safari Indonesia Puncak. Sensasi menginap unik dikelilingi alam liar dan satwa langka.',
  `facilities` = 'Akses Taman Safari, Rumah Pohon, Caravan Lodge, Kolam Renang, Restoran, Wifi Gratis'
WHERE `id` = 6;

-- Update data hotel 7: The Highland Park Resort (Kuntum)
UPDATE `nearby_hotels` SET
  `latitude` = -6.66698900,
  `longitude` = 106.74567200,
  `address` = 'Jl. Curug Nangka, Sinarwangi, Tamansari, Bogor, Jawa Barat 16610',
  `description` = 'Glamping bintang 4 unik dengan pemandangan Gunung Salak dan fasilitas rekreasi outbound lengkap.',
  `facilities` = 'Tenda Glamping, Waterpark, Outbound, Restoran, WiFi Gratis'
WHERE `id` = 7;

-- Update data hotel 8: Lembah Dewata Resort
UPDATE `nearby_hotels` SET
  `latitude` = -6.65481200,
  `longitude` = 106.87910200,
  `address` = 'Jl. Raya Puncak - Gadog KM. 79, Cisarua, Bogor, Jawa Barat 16750',
  `description` = 'Resort ramah keluarga di jalur utama Puncak dengan danau buatan indah dan fasilitas rekreasi lengkap.',
  `facilities` = 'Danau Buatan, Kolam Renang, Restoran, Taman Bunga, WiFi Gratis, Parkir Luas'
WHERE `id` = 8;

-- Update data hotel 9: Bukit Indah Puncak
UPDATE `nearby_hotels` SET
  `latitude` = -6.68912300,
  `longitude` = 106.95412300,
  `address` = 'Jl. Raya Puncak KM. 85, Cisarua, Kabupaten Bogor, Jawa Barat 16750',
  `description` = 'Hotel berbintang 3 yang nyaman dengan pemandangan lembah dan perbukitan Puncak yang menawan.',
  `facilities` = 'Kolam Renang, Restoran, Pemandangan Gunung, Ruang Rapat, WiFi Gratis'
WHERE `id` = 9;

-- Update data hotel 10: Puncak Pass Resort (Cimory)
UPDATE `nearby_hotels` SET
  `latitude` = -6.69854200,
  `longitude` = 106.98394400,
  `address` = 'Jl. Raya Puncak KM. 90, Cisarua, Bogor, Jawa Barat 16750',
  `description` = 'Resort lereng gunung dengan pemandangan kebun teh khas Puncak Bogor.',
  `facilities` = 'Kolam Renang, Kebun Teh, Restoran, WiFi Gratis'
WHERE `id` = 10;

-- Update data hotel 11: Lembah Dewata Resort (Cimory)
UPDATE `nearby_hotels` SET
  `latitude` = -6.65481200,
  `longitude` = 106.87910200,
  `address` = 'Jl. Raya Puncak KM. 79, Cisarua, Bogor, Jawa Barat 16750',
  `description` = 'Penginapan keluarga yang sejuk dekat dengan tempat wisata kuliner Cimory Riverside.',
  `facilities` = 'Kolam Renang, Restoran, Wifi, Taman'
WHERE `id` = 11;

-- Update data hotel 12: Bukit Indah Puncak (Cimory)
UPDATE `nearby_hotels` SET
  `latitude` = -6.68912300,
  `longitude` = 106.95412300,
  `address` = 'Jl. Raya Puncak KM. 85, Cisarua, Bogor, Jawa Barat 16750',
  `description` = 'Hotel nyaman dengan view pegunungan dekat area Megamendung & Cisarua.',
  `facilities` = 'Kolam Renang, Restoran, Wifi, Parkir'
WHERE `id` = 12;
