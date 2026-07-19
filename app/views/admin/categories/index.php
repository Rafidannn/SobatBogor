<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Kelola Kategori Wisata</h1>
        <button class="btn btn-success fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>

    <!-- Tabel Kategori -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4" style="width: 80px;">ID</th>
                            <th>Ikon</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Destinasi</th>
                            <th class="text-end px-4" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada kategori. Klik "Tambah Kategori" untuk memulai.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $index => $cat): ?>
                                <tr>
                                    <td class="px-4 text-muted fw-semibold"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fa <?= htmlspecialchars($cat['icon'] ?: 'fa-folder') ?> fs-5"></i>
                                        </div>
                                    </td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="text-muted small"><?= htmlspecialchars($cat['slug']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary rounded-pill"><?= $cat['total_destinations'] ?> Destinasi</span>
                                    </td>
                                    <td class="text-end px-4">
                                        <button class="btn btn-sm btn-outline-primary me-1 fw-semibold edit-cat-btn" 
                                                data-id="<?= $cat['id'] ?>" 
                                                data-name="<?= htmlspecialchars($cat['name']) ?>" 
                                                data-icon="<?= htmlspecialchars($cat['icon']) ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal">
                                            <i class="fa fa-edit me-1"></i> Edit
                                        </button>
                                        <form action="<?= BASE_URL ?>/admin/categories/delete/<?= $cat['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua destinasi di dalam kategori ini juga akan ikut terhapus!');">
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/admin/categories/store" method="POST">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="addCategoryModalLabel">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-content-body p-4">
                    <div class="mb-3">
                        <label for="add_name" class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" class="form-control" id="add_name" name="name" placeholder="Contoh: Wisata Air Terjun" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_icon" class="form-label fw-semibold">Ikon (Class FontAwesome)</label>
                        <input type="text" class="form-control" id="add_icon" name="icon" value="fa-tree" placeholder="Contoh: fa-tree, fa-umbrella, fa-utensils">
                        <div class="form-text">Gunakan nama class dari FontAwesome 6.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold">Simpan Kategori</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCategoryForm" action="" method="POST">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="editCategoryModalLabel">Ubah Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-content-body p-4">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label fw-semibold">Ikon (Class FontAwesome)</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JS binding data ke Modal Edit -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-cat-btn');
    const editForm = document.getElementById('editCategoryForm');
    const editNameInput = document.getElementById('edit_name');
    const editIconInput = document.getElementById('edit_icon');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const icon = this.getAttribute('data-icon');

            editForm.action = '<?= BASE_URL ?>/admin/categories/update/' + id;
            editNameInput.value = name;
            editIconInput.value = icon;
        });
    });
});
</script>
