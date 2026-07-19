<?php
require_once ROOT_PATH . '/app/models/Category.php';

class CategoryController extends Controller {
    private Category $categoryModel;

    public function __construct() {
        // Proteksi rute admin
        AdminMiddleware::handle();
        $this->categoryModel = new Category();
    }

    /**
     * Helper untuk membuat slug ramah URL
     */
    private function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    // Tampilkan daftar kategori
    public function index(): void {
        $categories = $this->categoryModel->getCategoriesWithCount();
        $this->view('admin/categories/index', [
            'title' => 'Kelola Kategori',
            'categories' => $categories
        ], 'admin');
    }

    // Simpan kategori baru
    public function store(): void {
        $name = trim($_POST['name'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-folder');

        if (empty($name)) {
            $_SESSION['error'] = 'Nama kategori wajib diisi.';
            $this->redirect('/admin/categories');
        }

        $slug = $this->slugify($name);
        
        // Buat slug unik jika tabrakan
        $originalSlug = $slug;
        $counter = 1;
        while ($this->categoryModel->isSlugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $success = $this->categoryModel->create([
            'name' => $name,
            'slug' => $slug,
            'icon' => $icon
        ]);

        if ($success) {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan kategori.';
        }

        $this->redirect('/admin/categories');
    }

    // Update kategori
    public function update(int $id): void {
        $name = trim($_POST['name'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-folder');

        if (empty($name)) {
            $_SESSION['error'] = 'Nama kategori tidak boleh kosong.';
            $this->redirect('/admin/categories');
        }

        $slug = $this->slugify($name);
        
        // Verifikasi keunikan slug kecuali kategori itu sendiri
        $originalSlug = $slug;
        $counter = 1;
        while ($this->categoryModel->isSlugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $success = $this->categoryModel->update($id, [
            'name' => $name,
            'slug' => $slug,
            'icon' => $icon
        ]);

        if ($success) {
            $_SESSION['success'] = 'Kategori berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui kategori.';
        }

        $this->redirect('/admin/categories');
    }

    // Hapus kategori
    public function delete(int $id): void {
        $success = $this->categoryModel->delete($id);
        
        if ($success) {
            $_SESSION['success'] = 'Kategori berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus kategori.';
        }

        $this->redirect('/admin/categories');
    }
}

