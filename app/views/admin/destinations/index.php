<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Kelola Destinasi Wisata</h1>
        <a href="<?= BASE_URL ?>/admin/destinations/create" class="btn btn-success fw-semibold shadow-sm">
            <i class="fa fa-plus me-1"></i> Tambah Destinasi
        </a>
    </div>

    <!-- Tabel Destinasi -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4" style="width: 80px;">No</th>
                            <th>Foto</th>
                            <th>Nama Wisata</th>
                            <th>Kategori</th>
                            <th>Harga Tiket</th>
                            <th>Jam Buka</th>
                            <th>Status</th>
                            <th class="text-end px-4" style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($destinations)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada destinasi wisata.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($destinations as $index => $dest): ?>
                                <tr>
                                    <td class="px-4 text-muted fw-semibold"><?= $index + 1 ?></td>
                                    <td>
                                        <?php if (!empty($dest['primary_image'])): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($dest['primary_image']) ?>" 
                                                 alt="<?= htmlspecialchars($dest['name']) ?>" 
                                                 class="rounded object-fit-cover" 
                                                 style="width: 60px; height: 45px;">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted small" 
                                                 style="width: 60px; height: 45px;">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($dest['name']) ?></div>
                                        <span class="text-muted small text-truncate d-inline-block" style="max-width: 250px;">
                                            <i class="fa fa-map-marker-alt me-1 text-danger small"></i><?= htmlspecialchars($dest['address']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><?= htmlspecialchars($dest['category_name']) ?></span>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <?= ($dest['ticket_price'] > 0) ? 'Rp ' . number_format($dest['ticket_price'], 0, ',', '.') : '<span class="text-success fw-bold">Gratis</span>' ?>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold"><?= htmlspecialchars($dest['open_hours'] ?: '-') ?></div>
                                        <?php if ($dest['open_hours']): ?>
                                            <?php $status = getDestinationStatus($dest['open_hours']); ?>
                                            <span class="badge mt-1" style="<?= $status['style'] ?>; font-size:0.65rem; padding:0.18rem 0.4rem; border-radius:30px; font-weight:600; display:inline-block;">
                                                <?= $status['label'] ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dest['is_featured'] == 1): ?>
                                            <span class="badge bg-warning text-dark"><i class="fa fa-star me-1"></i>Trending</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border">Standar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="<?= BASE_URL ?>/admin/destinations/edit/<?= $dest['id'] ?>" class="btn btn-sm btn-outline-primary me-1 fw-semibold">
                                            <i class="fa fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="<?= BASE_URL ?>/admin/destinations/delete/<?= $dest['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi wisata ini? Semua ulasan dan gambar terkait akan dihapus secara permanen!');">
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold">
                                                <i class="fa fa-trash-alt me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
