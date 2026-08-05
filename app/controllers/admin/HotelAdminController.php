<?php
namespace admin;

require_once ROOT_PATH . '/app/models/Hotel.php';
require_once ROOT_PATH . '/app/models/Destination.php';

class HotelAdminController
{
    private Hotel $hotelModel;
    private Destination $destinationModel;

    public function __construct()
    {
        $this->requireAdmin();
        $this->hotelModel       = new Hotel();
        $this->destinationModel = new Destination();
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /** Admin: List all hotels */
    public function index(): void
    {
        $hotels       = $this->hotelModel->getAll();
        $destinations = $this->destinationModel->getAll();
        require ROOT_PATH . '/app/views/admin/hotels/index.php';
        // Rendered via admin layout
        $this->render('admin/hotels/index', compact('hotels', 'destinations'));
    }

    /** Admin: Show create form */
    public function create(): void
    {
        $destinations = $this->destinationModel->getAll();
        $hotel        = null;
        $this->render('admin/hotels/form', compact('destinations', 'hotel'));
    }

    /** Admin: Store new hotel */
    public function store(): void
    {
        $data = $this->processFormData();
        $this->hotelModel->create($data);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hotel berhasil ditambahkan.'];
        header('Location: ' . BASE_URL . '/admin/hotels');
        exit;
    }

    /** Admin: Show edit form */
    public function edit(int $id): void
    {
        $hotel        = $this->hotelModel->findById($id);
        $destinations = $this->destinationModel->getAll();
        if (!$hotel) { header('Location: ' . BASE_URL . '/admin/hotels'); exit; }
        $this->render('admin/hotels/form', compact('destinations', 'hotel'));
    }

    /** Admin: Update hotel */
    public function update(int $id): void
    {
        $hotel = $this->hotelModel->findById($id);
        if (!$hotel) { header('Location: ' . BASE_URL . '/admin/hotels'); exit; }

        $data = $this->processFormData($hotel);
        $this->hotelModel->update($id, $data);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hotel berhasil diperbarui.'];
        header('Location: ' . BASE_URL . '/admin/hotels');
        exit;
    }

    /** Admin: Delete hotel */
    public function delete(int $id): void
    {
        $this->hotelModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Hotel berhasil dihapus.'];
        header('Location: ' . BASE_URL . '/admin/hotels');
        exit;
    }

    /** Process form data + handle image upload */
    private function processFormData(?array $existing = null): array
    {
        $imagePath = $existing['image_path'] ?? null;

        // Handle image upload
        if (!empty($_FILES['hotel_image']['tmp_name'])) {
            $file      = $_FILES['hotel_image'];
            $ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed) && $file['size'] <= 3 * 1024 * 1024) {
                $slug      = preg_replace('/[^a-z0-9-]/', '-', strtolower(trim($_POST['name'])));
                $dirName   = $slug;
                $uploadDir = ROOT_PATH . '/public/assets/uploads/hotels/' . $dirName . '/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $fileName  = 'main.' . $ext;
                move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);
                $imagePath = 'assets/uploads/hotels/' . $dirName . '/' . $fileName;
            }
        }

        return [
            'destination_id' => (int)($_POST['destination_id'] ?? 0),
            'name'           => trim($_POST['name'] ?? ''),
            'star_rating'    => (int)($_POST['star_rating'] ?? 3),
            'price_start'    => (float)($_POST['price_start'] ?? 0),
            'distance_text'  => trim($_POST['distance_text'] ?? ''),
            'latitude'       => !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null,
            'longitude'      => !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null,
            'address'        => trim($_POST['address'] ?? ''),
            'description'    => trim($_POST['description'] ?? ''),
            'facilities'     => trim($_POST['facilities'] ?? ''),
            'image_path'     => $imagePath,
            'traveloka_url'  => trim($_POST['traveloka_url'] ?? ''),
            'booking_url'    => trim($_POST['booking_url'] ?? ''),
            'is_active'      => isset($_POST['is_active']) ? 1 : 0,
        ];
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        $pageTitle = 'Kelola Hotel';
        ob_start();
        require ROOT_PATH . '/app/views/' . $view . '.php';
        $content = ob_get_clean();
        require ROOT_PATH . '/app/views/layouts/admin.php';
    }
}
