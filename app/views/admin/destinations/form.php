<div class="container-fluid" style="max-width: 900px;">
    <div class="mb-4">
        <a href="<?= BASE_URL ?>/admin/destinations" class="btn btn-sm btn-outline-secondary fw-semibold mb-2">
            <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <h1 class="h3 mb-0 text-gray-800 fw-bold"><?= htmlspecialchars($title) ?></h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <form action="<?= $isEdit ? BASE_URL . '/admin/destinations/update/' . $destination['id'] : BASE_URL . '/admin/destinations/store' ?>" 
                  method="POST" 
                  enctype="multipart/form-data">
                
                <!-- Nama Wisata -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Tempat Wisata</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= htmlspecialchars($destination['name'] ?? '') ?>" 
                           placeholder="Contoh: Curug Bidadari Sentul" required>
                </div>

                <div class="row">
                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" 
                                    <?= (isset($destination['category_id']) && $destination['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tiket Masuk -->
                    <div class="col-md-6 mb-3">
                        <label for="ticket_price" class="form-label fw-semibold">Harga Tiket Masuk (IDR)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="ticket_price" name="ticket_price" 
                                   value="<?= htmlspecialchars($destination['ticket_price'] ?? '0') ?>" 
                                   min="0" placeholder="0 = Gratis">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Jam Buka -->
                    <div class="col-md-6 mb-3">
                        <label for="open_hours" class="form-label fw-semibold">Jam Operasional</label>
                        <input type="text" class="form-control" id="open_hours" name="open_hours" 
                               value="<?= htmlspecialchars($destination['open_hours'] ?? '') ?>" 
                               placeholder="Contoh: 08:00 - 17:00 WIB">
                    </div>

                    <!-- Lat / Long Koordinat -->
                    <div class="col-md-3 col-6 mb-3">
                        <label for="latitude" class="form-label fw-semibold">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" 
                               value="<?= htmlspecialchars($destination['latitude'] ?? '') ?>" 
                               placeholder="Contoh: -6.5981">
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <label for="longitude" class="form-label fw-semibold">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" 
                               value="<?= htmlspecialchars($destination['longitude'] ?? '') ?>" 
                               placeholder="Contoh: 106.7994">
                    </div>
                </div>

                <!-- Alamat Fisik -->
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea class="form-control" id="address" name="address" rows="2" 
                              placeholder="Masukkan detail alamat jalan, kelurahan, kecamatan..." required><?= htmlspecialchars($destination['address'] ?? '') ?></textarea>
                </div>

                <!-- Deskripsi Wisata -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi Destinasi</label>
                    <textarea class="form-control" id="description" name="description" rows="5" 
                              placeholder="Tulis penjelasan lengkap, fasilitas, rute, atau daya tarik utama..." required><?= htmlspecialchars($destination['description'] ?? '') ?></textarea>
                </div>

                <!-- Status Trending / Featured -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" 
                               value="1" <?= (isset($destination['is_featured']) && $destination['is_featured'] == 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold text-warning" for="is_featured">
                            <i class="fa fa-star me-1"></i> Jadikan Destinasi Trending / Rekomendasi di Beranda
                        </label>
                    </div>
                </div>

                <!-- Kelola Gambar (Hanya Tampil saat Edit Mode) -->
                <?php if ($isEdit && !empty($images)): ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">Galeri Foto Destinasi</label>
                        <div class="row g-3">
                            <?php foreach ($images as $img): ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border rounded h-100">
                                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image_path']) ?>" 
                                             class="card-img-top object-fit-cover" 
                                             style="height: 140px;" alt="Galeri">
                                        <div class="card-body p-2 bg-light">
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="radio" name="primary_image_id" 
                                                       id="primary_<?= $img['id'] ?>" value="<?= $img['id'] ?>" 
                                                       <?= ($img['is_primary'] == 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label small fw-semibold text-success" for="primary_<?= $img['id'] ?>">
                                                    Utama (Thumbnail)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete_images[]" 
                                                       id="delete_<?= $img['id'] ?>" value="<?= $img['id'] ?>">
                                                <label class="form-check-label small text-danger" for="delete_<?= $img['id'] ?>">
                                                    <i class="fa fa-trash-alt me-1"></i>Hapus Foto ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-text mt-2 text-muted">Beri tanda centang 'Hapus' pada foto yang ingin dibuang, atau pilih 'Utama' untuk mengganti foto thumbnail.</div>
                    </div>
                <?php endif; ?>

                <!-- Input Upload Gambar Baru -->
                <div class="mb-4">
                    <label for="images" class="form-label fw-semibold"><?= $isEdit ? 'Upload Foto Tambahan' : 'Upload Foto Destinasi (Bisa pilih banyak sekaligus)' ?></label>
                    <input class="form-control" type="file" id="images" name="images[]" accept="image/*" multiple>
                    <div class="form-text">Format yang didukung: JPG, JPEG, PNG, WebP. Maksimal 2MB per gambar.</div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="<?= BASE_URL ?>/admin/destinations" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="fa fa-save me-1"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Destinasi' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
