<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Moderasi Ulasan Wisatawan</h1>
        <p class="text-muted small">Kelola kelayakan komentar dan rating bintang yang tampil di halaman detail.</p>
    </div>

    <!-- Tabel Moderasi Ulasan -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4" style="width: 80px;">No</th>
                            <th>Wisatawan</th>
                            <th>Destinasi Wisata</th>
                            <th>Rating</th>
                            <th style="max-width: 350px;">Komentar / Ulasan</th>
                            <th>Status</th>
                            <th class="text-end px-4" style="width: 250px;">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada ulasan masuk dari wisatawan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $index => $rev): ?>
                                <tr>
                                    <td class="px-4 text-muted fw-semibold"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($rev['user_name']) ?></div>
                                        <span class="text-muted small"><?= htmlspecialchars($rev['user_email']) ?></span>
                                    </td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($rev['destination_name']) ?></td>
                                    <td>
                                        <div class="text-nowrap">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star <?= ($i <= $rev['rating']) ? 'text-warning' : 'text-secondary opacity-25' ?> small"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td style="max-width: 350px;" class="small text-muted">
                                        <div class="text-wrap">
                                            "<?= htmlspecialchars($rev['comment'] ?: '(Hanya memberikan rating saja)') ?>"
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($rev['is_visible'] == 1): ?>
                                            <span class="badge bg-success-subtle text-success"><i class="fa fa-eye me-1"></i>Tampil</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger"><i class="fa fa-eye-slash me-1"></i>Disembunyikan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end px-4">
                                        <form action="<?= BASE_URL ?>/admin/reviews/hide/<?= $rev['id'] ?>" method="POST" class="d-inline">
                                            <?php if ($rev['is_visible'] == 1): ?>
                                                <button type="submit" class="btn btn-sm btn-outline-warning me-1 fw-semibold">
                                                    <i class="fa fa-eye-slash me-1"></i> Sembunyikan
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success me-1 fw-semibold">
                                                    <i class="fa fa-eye me-1"></i> Tampilkan
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                        <form action="<?= BASE_URL ?>/admin/reviews/delete/<?= $rev['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen dari database?');">
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
