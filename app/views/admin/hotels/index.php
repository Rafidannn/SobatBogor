<?php
$flashMsg = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php if ($flashMsg): ?>
<div class="alert alert-<?= $flashMsg['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm" style="border-radius:10px;">
    <i class="fas fa-<?= $flashMsg['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i>
    <?= htmlspecialchars($flashMsg['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Penginapan & Hotel</h1>
            <p class="text-muted small mb-0">Kelola rekomendasi hotel terdekat per destinasi wisata</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/hotels/create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Tambah Hotel
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4 py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Hotel</th>
                        <th class="py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Destinasi</th>
                        <th class="py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Bintang</th>
                        <th class="py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Harga Mulai</th>
                        <th class="py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Jarak</th>
                        <th class="py-3" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Status</th>
                        <th class="py-3 pe-4 text-end" style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hotels)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-hotel fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada data hotel. <a href="<?= BASE_URL ?>/admin/hotels/create">Tambah sekarang</a>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($hotels as $h): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <?php if ($h['image_path']): ?>
                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($h['image_path']) ?>"
                                     style="width:56px;height:42px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                                <?php else: ?>
                                <div style="width:56px;height:42px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-hotel text-muted"></i>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size:0.9rem;"><?= htmlspecialchars($h['name']) ?></div>
                                    <div class="text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($h['distance_text'] ?? '-') ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($h['destination_name']) ?></span></td>
                        <td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" style="font-size:0.75rem;color:<?= $i <= $h['star_rating'] ? '#f59e0b' : '#e2e8f0' ?>;"></i>
                            <?php endfor; ?>
                        </td>
                        <td class="fw-semibold text-dark" style="font-size:0.88rem;">
                            Rp <?= number_format($h['price_start'], 0, ',', '.') ?>
                        </td>
                        <td class="text-muted" style="font-size:0.85rem;"><?= htmlspecialchars($h['distance_text'] ?? '-') ?></td>
                        <td>
                            <?php if ($h['is_active']): ?>
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                            <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="<?= BASE_URL ?>/admin/hotels/edit/<?= $h['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/admin/hotels/delete/<?= $h['id'] ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus hotel ini?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i>
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
