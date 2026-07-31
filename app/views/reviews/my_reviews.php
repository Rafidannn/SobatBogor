<?php
// Helper render star rating
function renderStarsMyReview(float $rating): string {
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $out .= '<i class="fas fa-star" style="color:#f59e0b;font-size:0.9rem;"></i>';
        } elseif ($rating >= $i - 0.5) {
            $out .= '<i class="fas fa-star-half-alt" style="color:#f59e0b;font-size:0.9rem;"></i>';
        } else {
            $out .= '<i class="far fa-star" style="color:#d1d5db;font-size:0.9rem;"></i>';
        }
    }
    return $out;
}
?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#0f172a,#1e293b);padding:3.5rem 0 2.5rem;">
    <div class="container">
        <h1 style="color:#fff;font-weight:800;font-size:2rem;margin-bottom:0.4rem;">
            <i class="fas fa-comments me-2" style="color:#34d399;"></i>Ulasan Saya
        </h1>
        <p style="color:rgba(255,255,255,0.65);font-size:0.95rem;">
            Kelola dan pantau status ulasan destinasi yang telah kamu kirimkan
        </p>
    </div>
</div>

<div class="container py-5">

    <!-- Flash Message Notification -->
    <?php if (!empty($flashMsg)): ?>
    <div class="alert alert-<?= $flashMsg['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show mb-4 shadow-sm" role="alert" style="border-radius:12px;">
        <i class="<?= $flashMsg['type'] === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' ?> me-2"></i>
        <?= htmlspecialchars($flashMsg['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
    <!-- Empty State -->
    <div class="text-center py-5" data-aos="fade-up">
        <div style="width:100px;height:100px;border-radius:50%;background:var(--gray-100);margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;">
            <i class="far fa-comment-dots" style="font-size:2.5rem;color:var(--gray-500);"></i>
        </div>
        <h2 style="font-weight:700;color:var(--dark);font-size:1.5rem;">Belum Ada Ulasan</h2>
        <p style="color:var(--gray-500);max-width:440px;margin:0.5rem auto 2rem;">
            Kamu belum pernah menulis ulasan destinasi. Jelajahi tempat menarik di Bogor dan bagikan pengalamanmu!
        </p>
        <a href="<?= BASE_URL ?>/destinations" class="btn-primary-custom btn px-4">
            <i class="fas fa-compass me-2"></i>Jelajahi Wisata
        </a>
    </div>

    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($reviews as $rv): ?>
        <div class="col-12" data-aos="fade-up">
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px;background:#fff;transition:transform 0.2s;">
                <div class="d-flex flex-column flex-md-row gap-4 align-items-start justify-content-between">
                    
                    <!-- Destination & Review Info -->
                    <div class="d-flex gap-3 align-items-start flex-grow-1">
                        <!-- Thumbnail Destinasi -->
                        <div style="width:90px;height:90px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#f1f5f9;">
                            <?php if (!empty($rv['destination_image'])): ?>
                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($rv['destination_image']) ?>" 
                                 alt="<?= htmlspecialchars($rv['destination_name']) ?>" 
                                 style="width:100%;height:100%;object-fit:cover;">
                            <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-mountain text-muted" style="font-size:1.8rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info Content -->
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <a href="<?= BASE_URL ?>/destinations/<?= htmlspecialchars($rv['destination_slug']) ?>" 
                                   class="fw-bold text-dark text-decoration-none" style="font-size:1.1rem;">
                                    <?= htmlspecialchars($rv['destination_name']) ?>
                                </a>

                                <!-- Status Badge -->
                                <?php if ($rv['is_visible'] == 1): ?>
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill" style="font-size:0.75rem;font-weight:600;">
                                    <i class="fas fa-check-circle me-1"></i>Dipublikasikan
                                </span>
                                <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill" style="font-size:0.75rem;font-weight:600;">
                                    <i class="fas fa-clock me-1"></i>Menunggu Moderasi
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div><?= renderStarsMyReview((float)$rv['rating']) ?></div>
                                <span class="text-muted" style="font-size:0.8rem;">
                                    • <?= date('d M Y, H:i', strtotime($rv['created_at'])) ?>
                                </span>
                            </div>

                            <?php if (!empty($rv['comment'])): ?>
                            <p class="text-secondary mb-2" style="font-size:0.92rem;line-height:1.6;">
                                "<?= nl2br(htmlspecialchars($rv['comment'])) ?>"
                            </p>
                            <?php endif; ?>

                            <?php if (!empty($rv['photo_path'])): ?>
                            <div class="mt-2">
                                <a href="<?= BASE_URL ?>/<?= htmlspecialchars($rv['photo_path']) ?>" target="_blank">
                                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($rv['photo_path']) ?>" 
                                         alt="Foto Ulasan" 
                                         style="height:65px;width:65px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 align-self-md-start flex-shrink-0">
                        <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" 
                                data-bs-toggle="modal" data-bs-target="#editReviewModal<?= $rv['id'] ?>">
                            <i class="fas fa-edit me-1"></i>Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" 
                                data-bs-toggle="modal" data-bs-target="#deleteReviewModal<?= $rv['id'] ?>">
                            <i class="fas fa-trash-alt me-1"></i>Hapus
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Edit Ulasan -->
        <div class="modal fade" id="editReviewModal<?= $rv['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="border-radius:16px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Ulasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= BASE_URL ?>/reviews/update/<?= $rv['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="modal-body pt-3">
                            <input type="hidden" name="redirect_to" value="my_reviews">
                            
                            <p class="text-muted small mb-3">
                                Ulasan untuk: <strong><?= htmlspecialchars($rv['destination_name']) ?></strong>
                            </p>

                            <!-- Rating Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-600 small">Rating Bintang</label>
                                <div class="d-flex gap-2">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <label style="cursor:pointer;font-size:1.5rem;color:#d1d5db;">
                                        <input type="radio" name="rating" value="<?= $s ?>" 
                                               <?= (int)$rv['rating'] === $s ? 'checked' : '' ?> required style="display:none;"
                                               onchange="updateModalStars(<?= $rv['id'] ?>, <?= $s ?>)">
                                        <i class="<?= (int)$rv['rating'] >= $s ? 'fas fa-star text-warning' : 'far fa-star' ?> modal-star-<?= $rv['id'] ?>" 
                                           data-val="<?= $s ?>"></i>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Comment Input -->
                            <div class="mb-3">
                                <label class="form-label fw-600 small">Komentar Ulasan</label>
                                <textarea name="comment" rows="3" class="form-control" style="border-radius:10px;font-size:0.9rem;"
                                          placeholder="Tuliskan ulasanmu..."><?= htmlspecialchars($rv['comment'] ?? '') ?></textarea>
                            </div>

                            <!-- Photo Input / Replace / Remove -->
                            <div class="mb-3">
                                <label class="form-label fw-600 small">Foto Ulasan (Opsional)</label>
                                <?php if (!empty($rv['photo_path'])): ?>
                                <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($rv['photo_path']) ?>" style="height:50px;width:50px;object-fit:cover;border-radius:6px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rmPhoto<?= $rv['id'] ?>">
                                        <label class="form-check-label text-danger small fw-600" for="rmPhoto<?= $rv['id'] ?>">
                                            Hapus Foto Ini
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <input type="file" name="review_photo" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                                <span class="text-muted style-small" style="font-size:0.75rem;">Upload foto baru untuk mengganti foto lama (Maks 3MB)</span>
                            </div>

                            <div class="alert alert-info py-2 small mb-0" style="border-radius:8px;">
                                <i class="fas fa-info-circle me-1"></i> Perubahan ulasan akan ditinjau kembali oleh Admin sebelum dipublikasikan.
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Delete Ulasan -->
        <div class="modal fade" id="deleteReviewModal<?= $rv['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="border-radius:16px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-danger">Hapus Ulasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= BASE_URL ?>/reviews/delete/<?= $rv['id'] ?>" method="POST">
                        <input type="hidden" name="redirect_to" value="my_reviews">
                        <div class="modal-body pt-3">
                            Apakah kamu yakin ingin menghapus ulasan untuk <strong><?= htmlspecialchars($rv['destination_name']) ?></strong>? Tindakan ini tidak dapat dibatalkan.
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus Ulasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<script>
function updateModalStars(reviewId, selectedVal) {
    const stars = document.querySelectorAll('.modal-star-' + reviewId);
    stars.forEach(star => {
        const val = parseInt(star.getAttribute('data-val'));
        if (val <= selectedVal) {
            star.className = 'fas fa-star text-warning modal-star-' + reviewId;
        } else {
            star.className = 'far fa-star modal-star-' + reviewId;
        }
    });
}
</script>
