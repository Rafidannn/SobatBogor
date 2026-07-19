<?php
require_once ROOT_PATH . '/app/models/Destination.php';
require_once ROOT_PATH . '/app/models/Category.php';
require_once ROOT_PATH . '/app/models/DestinationImage.php';

class DestinationAdminController extends Controller {
    private Destination $destinationModel;
    private Category $categoryModel;
    private DestinationImage $imageModel;

    public function __construct() {
        AdminMiddleware::handle();
        $this->destinationModel = new Destination();
        $this->categoryModel = new Category();
        $this->imageModel = new DestinationImage();
    }

    private function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    // Tampilkan daftar destinasi
    public function index(): void {
        $destinations = $this->destinationModel->findAllWithCategory();
        $this->view('admin/destinations/index', [
            'title' => 'Kelola Destinasi Wisata',
            'destinations' => $destinations
        ], 'admin');
    }

    // Tampilkan form tambah destinasi
    public function create(): void {
        $categories = $this->categoryModel->findAll();
        $this->view('admin/destinations/form', [
            'title' => 'Tambah Destinasi Baru',
            'categories' => $categories,
            'isEdit' => false
        ], 'admin');
    }

    // Simpan destinasi baru ke database beserta file gambar
    public function store(): void {
        $name        = trim($_POST['name'] ?? '');
        $categoryId  = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $address     = trim($_POST['address'] ?? '');
        $latitude    = !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null;
        $longitude   = !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null;
        $ticketPrice = (float) ($_POST['ticket_price'] ?? 0);
        $openHours   = trim($_POST['open_hours'] ?? '');
        $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;

        if (empty($name) || empty($categoryId)) {
            $_SESSION['error'] = 'Nama destinasi dan kategori wajib diisi.';
            $this->redirect('/admin/destinations/create');
        }

        $slug = $this->slugify($name);
        $originalSlug = $slug;
        $counter = 1;
        while ($this->destinationModel->isSlugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $success = $this->destinationModel->create([
            'category_id'  => $categoryId,
            'name'         => $name,
            'slug'         => $slug,
            'description'  => $description,
            'address'      => $address,
            'latitude'     => $latitude,
            'longitude'    => $longitude,
            'ticket_price' => $ticketPrice,
            'open_hours'   => $openHours,
            'is_featured'  => $isFeatured
        ]);

        if ($success) {
            $destinationId = $this->destinationModel->lastInsertId();
            
            // Proses Upload Gambar Multi-File
            if (!empty($_FILES['images']['name'][0])) {
                $this->uploadImages($destinationId, $_FILES['images']);
            }
            
            $_SESSION['success'] = 'Destinasi wisata berhasil disimpan.';
        } else {
            $_SESSION['error'] = 'Gagal menyimpan destinasi wisata.';
        }

        $this->redirect('/admin/destinations');
    }

    // Tampilkan form edit destinasi
    public function edit(int $id): void {
        $destination = $this->destinationModel->findById($id);
        
        if (!$destination) {
            $_SESSION['error'] = 'Destinasi tidak ditemukan.';
            $this->redirect('/admin/destinations');
        }

        $categories = $this->categoryModel->findAll();
        $images     = $this->imageModel->getImagesByDestination($id);

        $this->view('admin/destinations/form', [
            'title' => 'Edit Destinasi - ' . $destination['name'],
            'categories' => $categories,
            'destination' => $destination,
            'images' => $images,
            'isEdit' => true
        ], 'admin');
    }

    // Update data destinasi & gambar
    public function update(int $id): void {
        $destination = $this->destinationModel->findById($id);
        if (!$destination) {
            $_SESSION['error'] = 'Destinasi tidak ditemukan.';
            $this->redirect('/admin/destinations');
        }

        $name        = trim($_POST['name'] ?? '');
        $categoryId  = (int) ($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $address     = trim($_POST['address'] ?? '');
        $latitude    = !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null;
        $longitude   = !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null;
        $ticketPrice = (float) ($_POST['ticket_price'] ?? 0);
        $openHours   = trim($_POST['open_hours'] ?? '');
        $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;

        if (empty($name) || empty($categoryId)) {
            $_SESSION['error'] = 'Nama destinasi dan kategori wajib diisi.';
            $this->redirect('/admin/destinations/edit/' . $id);
        }

        $slug = $this->slugify($name);
        $originalSlug = $slug;
        $counter = 1;
        while ($this->destinationModel->isSlugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Jalankan Update
        $this->destinationModel->update($id, [
            'category_id'  => $categoryId,
            'name'         => $name,
            'slug'         => $slug,
            'description'  => $description,
            'address'      => $address,
            'latitude'     => $latitude,
            'longitude'    => $longitude,
            'ticket_price' => $ticketPrice,
            'open_hours'   => $openHours,
            'is_featured'  => $isFeatured
        ]);

        // Tangani penetapan gambar utama baru jika diubah
        if (isset($_POST['primary_image_id'])) {
            $primaryImageId = (int) $_POST['primary_image_id'];
            $this->imageModel->resetPrimary($id);
            $this->imageModel->update($primaryImageId, ['is_primary' => 1]);
        }

        // Tangani penghapusan gambar yang dicentang
        if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
                $img = $this->imageModel->findById((int) $imgId);
                if ($img) {
                    // Hapus fisik file gambar dari server
                    $fullPath = ROOT_PATH . '/public/' . $img['image_path'];
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                    // Hapus record DB
                    $this->imageModel->delete($img['id']);
                }
            }
        }

        // Proses Upload Gambar Tambahan jika ada
        if (!empty($_FILES['images']['name'][0])) {
            $this->uploadImages($id, $_FILES['images']);
        }

        $_SESSION['success'] = 'Destinasi wisata berhasil diperbarui.';
        $this->redirect('/admin/destinations');
    }

    // Hapus destinasi dan semua gambar fisiknya
    public function delete(int $id): void {
        $images = $this->imageModel->getImagesByDestination($id);
        
        // Hapus semua file gambar fisik
        foreach ($images as $img) {
            $fullPath = ROOT_PATH . '/public/' . $img['image_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $success = $this->destinationModel->delete($id);

        if ($success) {
            $_SESSION['success'] = 'Destinasi wisata berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus destinasi wisata.';
        }

        $this->redirect('/admin/destinations');
    }

    /**
     * Helper Method untuk Menangani Proses Upload File Gambar
     */
    private function uploadImages(int $destinationId, array $files): void {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $uploadDir = ROOT_PATH . '/public/assets/uploads/';

        // Ambil info status gambar utama di DB untuk tahu jika gambar baru adalah yang pertama
        $existingImages = $this->imageModel->getImagesByDestination($destinationId);
        $hasPrimary = false;
        foreach ($existingImages as $img) {
            if ($img['is_primary'] == 1) {
                $hasPrimary = true;
                break;
            }
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileName = $files['name'][$i];
            $fileSize = $files['size'][$i];
            $tmpPath  = $files['tmp_name'][$i];

            // Validasi Ukuran File (Maksimal 2MB)
            if ($fileSize > 2 * 1024 * 1024) {
                continue;
            }

            // Validasi Ekstensi
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                continue;
            }

            // Buat Nama File Acak Unik
            $newFileName = 'dest_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpPath, $destPath)) {
                // Tentukan status is_primary
                $isPrimary = (!$hasPrimary && $i === 0) ? 1 : 0;
                if ($isPrimary) {
                    $hasPrimary = true;
                }

                $this->imageModel->create([
                    'destination_id' => $destinationId,
                    'image_path'     => 'assets/uploads/' . $newFileName,
                    'is_primary'     => $isPrimary
                ]);
            }
        }
    }
}

