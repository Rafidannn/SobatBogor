<?php $isEdit = !empty($hotel); ?>

<div class="container-fluid">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <div>
            <h1 class="h4 fw-bold mb-0"><?= $isEdit ? 'Edit Hotel' : 'Tambah Hotel Baru' ?></h1>
            <p class="text-muted small mb-0">Isi detail informasi hotel terdekat dari destinasi wisata</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:14px;max-width:760px;">
        <div class="card-body p-4">
            <form action="<?= BASE_URL ?>/admin/hotels/<?= $isEdit ? 'update/' . $hotel['id'] : 'store' ?>"
                  method="POST" enctype="multipart/form-data">

                <!-- Destinasi -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Destinasi Wisata <span class="text-danger">*</span></label>
                    <select name="destination_id" class="form-select" required>
                        <option value="">-- Pilih Destinasi --</option>
                        <?php foreach ($destinations as $dest): ?>
                        <option value="<?= $dest['id'] ?>" <?= ($isEdit && $hotel['destination_id'] == $dest['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dest['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nama Hotel -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Hotel <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="<?= htmlspecialchars($hotel['name'] ?? '') ?>"
                           placeholder="Contoh: Novotel Bogor Golf Resort" required>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Bintang -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Rating Bintang <span class="text-danger">*</span></label>
                        <select name="star_rating" class="form-select" required>
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                            <option value="<?= $s ?>" <?= ($isEdit && $hotel['star_rating'] == $s) ? 'selected' : ($s == 3 ? 'selected' : '') ?>>
                                <?= $s ?> Bintang
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Harga Mulai -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Harga Mulai (Rp/malam) <span class="text-danger">*</span></label>
                        <input type="number" name="price_start" class="form-control"
                               value="<?= $hotel['price_start'] ?? '' ?>"
                               placeholder="Contoh: 750000" min="0" required>
                    </div>

                    <!-- Jarak -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Jarak dari Destinasi</label>
                        <input type="text" name="distance_text" class="form-control"
                               value="<?= htmlspecialchars($hotel['distance_text'] ?? '') ?>"
                               placeholder="Contoh: 1.2 km dari lokasi">
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Alamat Lengkap</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($hotel['address'] ?? '') ?>"
                           placeholder="Contoh: Jl. Raya Puncak KM. 90, Cisarua, Bogor">
                </div>

                <div class="row g-3 mb-3">
                    <!-- Latitude -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Latitude (Peta)</label>
                        <input type="text" name="latitude" class="form-control"
                               value="<?= htmlspecialchars($hotel['latitude'] ?? '') ?>"
                               placeholder="Contoh: -6.6111162">
                    </div>

                    <!-- Longitude -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Longitude (Peta)</label>
                        <input type="text" name="longitude" class="form-control"
                               value="<?= htmlspecialchars($hotel['longitude'] ?? '') ?>"
                               placeholder="Contoh: 106.8285575">
                    </div>
                </div>

                <!-- Deskripsi Hotel -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Deskripsi Hotel</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Tuliskan deskripsi ringkas mengenai hotel ini..."><?= htmlspecialchars($hotel['description'] ?? '') ?></textarea>
                </div>

                <!-- Fasilitas Hotel -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Fasilitas Hotel (pisahkan dengan koma)</label>
                    <input type="text" name="facilities" class="form-control"
                           value="<?= htmlspecialchars($hotel['facilities'] ?? '') ?>"
                           placeholder="Contoh: Kolam Renang, WiFi Gratis, Restoran, Spa, AC, Parkir Gratis">
                </div>

                <!-- Foto Hotel -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Foto Hotel</label>
                    <?php if ($isEdit && !empty($hotel['image_path'])): ?>
                    <div class="mb-2">
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($hotel['image_path']) ?>"
                             style="height:80px;border-radius:8px;object-fit:cover;">
                        <small class="text-muted d-block mt-1">Foto saat ini. Upload baru untuk mengganti.</small>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="hotel_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted">Format: JPG, PNG, WebP. Maks 3MB.</small>
                </div>

                <!-- Link Traveloka -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <img src="https://www.traveloka.com/favicon.ico" style="height:14px;margin-right:4px;" onerror="this.style.display='none'">
                        Link Traveloka
                    </label>
                    <input type="url" name="traveloka_url" class="form-control"
                           value="<?= htmlspecialchars($hotel['traveloka_url'] ?? '') ?>"
                           placeholder="https://www.traveloka.com/...">
                </div>

                <!-- Link Booking.com -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <i class="fas fa-globe me-1 text-primary"></i>Link Booking.com
                    </label>
                    <input type="url" name="booking_url" class="form-control"
                           value="<?= htmlspecialchars($hotel['booking_url'] ?? '') ?>"
                           placeholder="https://www.booking.com/...">
                </div>

                <!-- Link Video YouTube -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <i class="fab fa-youtube me-1 text-danger"></i>Link Video YouTube (Virtual Tour)
                    </label>
                    <input type="url" name="video_url" class="form-control"
                           value="<?= htmlspecialchars($hotel['video_url'] ?? '') ?>"
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>

                <!-- Status Aktif -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                               <?= (!$isEdit || $hotel['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold small" for="isActive">Tampilkan di halaman destinasi</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Hotel' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
