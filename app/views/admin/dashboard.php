<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Beranda Admin</h1>
        <p class="text-muted small">Ringkasan data SobatBogor.</p>
    </div>

    <!-- Baris Card Statistik -->
    <div class="row">
        <!-- Card Kategori -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 border-start border-primary border-4 shadow-sm h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 small fw-semibold">Kategori</div>
                            <div class="h5 mb-0 font-weight-bold text-dark fw-bold"><?= $stats['categories'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-tags fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Destinasi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 border-start border-success border-4 shadow-sm h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1 small fw-semibold">Destinasi</div>
                            <div class="h5 mb-0 font-weight-bold text-dark fw-bold"><?= $stats['destinations'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-map-marker-alt fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Ulasan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 border-start border-info border-4 shadow-sm h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1 small fw-semibold">Ulasan Pengunjung</div>
                            <div class="h5 mb-0 font-weight-bold text-dark fw-bold"><?= $stats['reviews'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-comments fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card User -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 border-start border-warning border-4 shadow-sm h-100 py-2 bg-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 small fw-semibold">User Wisatawan</div>
                            <div class="h5 mb-0 font-weight-bold text-dark fw-bold"><?= $stats['users'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa fa-users fa-2x text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Detail Destinasi dan Ulasan Baru -->
    <div class="row mt-4">
        <!-- Kolom Destinasi Baru -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold"><i class="fa fa-map-marked-alt me-2 text-success"></i>Destinasi Terakhir Ditambahkan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($latestDestinations)): ?>
                            <div class="text-center py-4 text-muted">Belum ada destinasi wisata.</div>
                        <?php else: ?>
                            <?php foreach ($latestDestinations as $dest): ?>
                                <a href="<?= BASE_URL ?>/admin/destinations" class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($dest['name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($dest['open_hours']) ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small text-truncate"><?= htmlspecialchars($dest['address']) ?></p>
                                    <small class="badge bg-success-subtle text-success"><?= htmlspecialchars($dest['category_name']) ?></small>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Ulasan Terbaru -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="m-0 font-weight-bold text-dark fw-bold"><i class="fa fa-comments me-2 text-info"></i>Ulasan Terakhir Masuk</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($latestReviews)): ?>
                            <div class="text-center py-4 text-muted">Belum ada ulasan masuk.</div>
                        <?php else: ?>
                            <?php foreach ($latestReviews as $rev): ?>
                                <div class="list-group-item py-3">
                                    <div class="d-flex w-100 justify-content-between mb-1">
                                        <span class="fw-bold text-dark small"><?= htmlspecialchars($rev['user_name']) ?></span>
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star <?= ($i <= $rev['rating']) ? 'text-warning' : 'text-secondary opacity-25' ?> small"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mb-1">Pada: <strong><?= htmlspecialchars($rev['destination_name']) ?></strong></small>
                                    <p class="mb-0 text-muted small text-italic">"<?= htmlspecialchars($rev['comment'] ?: '(Tanpa komentar)') ?>"</p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
