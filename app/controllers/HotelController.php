<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/models/Hotel.php';
require_once ROOT_PATH . '/app/models/Destination.php';

/**
 * HotelController.php
 * Menangani katalog penginapan/hotel publik dan halaman detail hotel.
 */
class HotelController extends Controller
{
    private Hotel $hotelModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
    }

    /**
     * GET /hotels
     * Menampilkan katalog hotel publik dengan filter dan Peta Leaflet.
     */
    public function index(): void
    {
        $search   = trim($_GET['q'] ?? '');
        $star     = (int)($_GET['star'] ?? 0);
        $maxPrice = (float)($_GET['max_price'] ?? 0);

        $hotels = $this->hotelModel->getFilteredPublic($search, $star, $maxPrice);

        $this->view('hotels/catalog', [
            'title'    => 'Penginapan & Hotel di Bogor',
            'metaDesc' => 'Temukan hotel dan penginapan terbaik di Bogor dekat tempat wisata pilihanmu.',
            'hotels'   => $hotels,
            'search'   => $search,
            'star'     => $star,
            'maxPrice' => $maxPrice,
        ]);
    }

    /**
     * GET /hotels/{id}
     * Menampilkan halaman detail hotel.
     */
    public function detail(string $id): void
    {
        $hotelId = (int)$id;
        $hotel   = $this->hotelModel->findByIdWithDestination($hotelId);

        if (!$hotel || !$hotel['is_active']) {
            http_response_code(404);
            die('<h2 style="text-align:center;padding:4rem;">Hotel tidak ditemukan.</h2>');
        }

        // Hotel lain terdekat / rekomendasi (sama destinasi)
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT * FROM nearby_hotels
             WHERE destination_id = ? AND id != ? AND is_active = 1
             LIMIT 3"
        );
        $stmt->execute([$hotel['destination_id'], $hotelId]);
        $otherHotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('hotels/detail', [
            'title'       => $hotel['name'] . ' — Penginapan Bogor',
            'metaDesc'    => substr(strip_tags($hotel['description'] ?? ''), 0, 155),
            'hotel'       => $hotel,
            'otherHotels' => $otherHotels,
        ]);
    }
}
