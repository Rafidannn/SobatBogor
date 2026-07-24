<?php
require_once ROOT_PATH . '/app/models/DestinationLink.php';
require_once ROOT_PATH . '/app/models/Destination.php';

class DestinationLinkAdminController extends Controller {
    private DestinationLink $linkModel;
    private Destination $destinationModel;

    public function __construct() {
        AdminMiddleware::handle();
        $this->linkModel        = new DestinationLink();
        $this->destinationModel = new Destination();
    }

    public function save(int $destinationId): void {
        $destination = $this->destinationModel->findById($destinationId);
        if (!$destination) {
            $_SESSION['error'] = 'Destinasi tidak ditemukan.';
            $this->redirect('/admin/destinations');
        }

        $label    = trim($_POST['link_label'] ?? 'Pesan Tiket');
        $url      = trim($_POST['link_url'] ?? '');
        $isActive = isset($_POST['link_is_active']) ? 1 : 0;

        if (empty($label)) {
            $label = 'Pesan Tiket';
        }

        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL) === false) {
            $_SESSION['error'] = 'URL link tiket tidak valid.';
            $this->redirect('/admin/destinations/edit/' . $destinationId);
        }

        if (!empty($url)) {
            $this->linkModel->saveForDestination($destinationId, $label, $url, $isActive);
            $_SESSION['success'] = 'Link tiket berhasil disimpan.';
        } else {
            $_SESSION['success'] = 'Tidak ada perubahan link tiket.';
        }

        $this->redirect('/admin/destinations/edit/' . $destinationId);
    }
}
