-- Migration: Tambah link beli/pesan tiket untuk seluruh destinasi

INSERT INTO destination_links (destination_id, label, url, is_active) VALUES
(1, 'Pesan Tiket Resmi', 'https://tiketkebunraya.id', 1),
(2, 'Pesan Tiket Resmi', 'https://ticket.tamansafaribogor.com', 1),
(3, 'Pesan Tiket Traveloka', 'https://www.traveloka.com/id-id/activities/indonesia/product/kuntum-farmfield-tickets-2000146469838', 1),
(5, 'Pesan Tiket Traveloka', 'https://www.traveloka.com/id-id/activities/indonesia/product/cimory-dairyland-riverside-puncak-7581597944715', 1),
(6, 'Info Tiket Resmi', 'https://tamanbunga.co.id/harga-tiket-masuk/', 1),
(7, 'Pesan Tiket Tiket.com', 'https://www.tiket.com/id-id/to-do/bogor-aquagame', 1),
(8, 'Pesan Paket Tiket.com', 'https://www.tiket.com/id-id/to-do/fun-offroad-cisadon-sentul-bogor-by-go-explore', 1),
(9, 'Info Tiket Resmi', 'https://gunungpancar.com/harga-tiket-masuk-gunung-pancar/', 1)
ON DUPLICATE KEY UPDATE 
url = VALUES(url), 
label = VALUES(label), 
is_active = VALUES(is_active);
