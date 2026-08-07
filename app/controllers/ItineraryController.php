<?php
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/app/models/Category.php';

/**
 * ItineraryController.php
 * Menangani fitur Smart Itinerary Planner (Perencana Rencana Perjalanan Otomatis)
 */
class ItineraryController extends Controller {

    public function index(): void {
        $db            = Database::getInstance()->getConnection();
        $categoryModel = new Category();

        $categories = $categoryModel->getCategoriesWithCount();

        // Parameter input
        $duration   = isset($_GET['duration']) ? (int)$_GET['duration'] : 1; // 1, 2, 3 hari
        $duration   = in_array($duration, [1, 2, 3]) ? $duration : 1;
        
        $budget     = trim($_GET['budget'] ?? 'standar'); // 'ekonomis', 'standar', 'mewah'
        $budget     = in_array($budget, ['ekonomis', 'standar', 'mewah']) ? $budget : 'standar';
        
        $selectedCats = isset($_GET['categories']) && is_array($_GET['categories']) 
                        ? array_map('trim', $_GET['categories']) 
                        : [];

        $hasGenerated = isset($_GET['generate']) && $_GET['generate'] == '1';
        $itinerary    = null;

        if ($hasGenerated) {
            $itinerary = $this->generateItinerary($db, $duration, $budget, $selectedCats);
        }

        $this->view('itinerary/index', [
            'title'        => 'Smart Itinerary Planner - Perencana Liburan Bogor',
            'metaDesc'     => 'Buat rencana liburan otomatis ke Bogor sesuai durasi, preferensi tempat wisata, dan budget kamu.',
            'categories'   => $categories,
            'duration'     => $duration,
            'budget'       => $budget,
            'selectedCats' => $selectedCats,
            'hasGenerated' => $hasGenerated,
            'itinerary'    => $itinerary,
        ]);
    }

    /**
     * Algoritma penyusunan jadwal perjalanan berdasarkan kriteria
     */
    private function generateItinerary(PDO $db, int $duration, string $budget, array $selectedCatSlugs): array {
        // Build base SQL filter
        $where = [];
        $params = [];

        if (!empty($selectedCatSlugs)) {
            $inPlaceholders = implode(',', array_fill(0, count($selectedCatSlugs), '?'));
            $where[] = "c.slug IN ({$inPlaceholders})";
            $params = array_merge($params, $selectedCatSlugs);
        }

        // Budget max price per ticket
        if ($budget === 'ekonomis') {
            $where[] = "(d.ticket_price IS NULL OR d.ticket_price <= 30000 OR d.ticket_price_weekday <= 30000)";
        } elseif ($budget === 'standar') {
            $where[] = "(d.ticket_price IS NULL OR d.ticket_price <= 100000 OR d.ticket_price_weekday <= 100000)";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Ambil semua destinasi sesuai kriteria
        $sql = "SELECT d.*, c.name AS category_name, c.icon AS category_icon,
                       (SELECT image_path FROM destination_images WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                       COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating
                FROM destinations d
                LEFT JOIN categories c ON d.category_id = c.id
                {$whereSql}
                ORDER BY RAND()";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $allDestinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fallback jika pencarian terlalu ketat
        if (count($allDestinations) < ($duration * 2)) {
            $stmt = $db->query("SELECT d.*, c.name AS category_name, c.icon AS category_icon,
                                       (SELECT image_path FROM destination_images WHERE destination_id = d.id AND is_primary = 1 LIMIT 1) AS primary_image,
                                       COALESCE((SELECT ROUND(AVG(rating),1) FROM reviews WHERE destination_id = d.id AND is_visible = 1), 0) AS avg_rating
                                FROM destinations d
                                LEFT JOIN categories c ON d.category_id = c.id
                                ORDER BY RAND()");
            $allDestinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Pisahkan kategori kuliner dan non-kuliner jika ada
        $culinaryDestinations = array_filter($allDestinations, function($d) {
            return str_contains(strtolower($d['category_name'] ?? ''), 'kuliner') || str_contains(strtolower($d['name']), 'resto') || str_contains(strtolower($d['name']), 'soto') || str_contains(strtolower($d['name']), 'cafe');
        });
        
        $sightseeingDestinations = array_filter($allDestinations, function($d) {
            return !str_contains(strtolower($d['category_name'] ?? ''), 'kuliner');
        });

        if (empty($sightseeingDestinations)) $sightseeingDestinations = $allDestinations;

        // Ambil data hotel jika durasi > 1
        $hotels = [];
        if ($duration > 1) {
            $hotelSql = "SELECT h.*, d.name AS destination_name
                         FROM nearby_hotels h
                         JOIN destinations d ON d.id = h.destination_id
                         WHERE h.is_active = 1";
            if ($budget === 'ekonomis') {
                $hotelSql .= " AND h.price_start <= 400000";
            } elseif ($budget === 'standar') {
                $hotelSql .= " AND h.price_start <= 900000";
            }
            $hotelSql .= " ORDER BY RAND()";
            $hStmt = $db->query($hotelSql);
            $hotels = $hStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($hotels)) {
                $hStmt = $db->query("SELECT h.*, d.name AS destination_name FROM nearby_hotels h JOIN destinations d ON d.id = h.destination_id WHERE h.is_active = 1 ORDER BY RAND()");
                $hotels = $hStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // Susun per-hari (Day 1, Day 2, etc)
        $days = [];
        $usedDestIds = [];
        $totalTicketCost = 0;
        $totalHotelCost  = 0;

        for ($dayNum = 1; $dayNum <= $duration; $dayNum++) {
            // Ambil destinasi Pagi
            $pagi = $this->pickUnused($sightseeingDestinations, $usedDestIds);
            if ($pagi) $usedDestIds[] = $pagi['id'];

            // Ambil destinasi Sore
            $sore = $this->pickUnused($sightseeingDestinations, $usedDestIds);
            if ($sore) $usedDestIds[] = $sore['id'];

            // Ambil rekomendasi Kuliner Siang
            $kuliner = $this->pickUnused($culinaryDestinations, $usedDestIds);
            if ($kuliner) {
                $usedDestIds[] = $kuliner['id'];
            }

            // Hitung tiket
            $pagiPrice = (float)($pagi['ticket_price_weekday'] ?? $pagi['ticket_price'] ?? 0);
            $sorePrice = (float)($sore['ticket_price_weekday'] ?? $sore['ticket_price'] ?? 0);
            $totalTicketCost += ($pagiPrice + $sorePrice);

            // Hotel malam (jika belum hari terakhir)
            $hotelMalam = null;
            if ($dayNum < $duration && !empty($hotels)) {
                $hotelMalam = $hotels[($dayNum - 1) % count($hotels)];
                $totalHotelCost += (float)$hotelMalam['price_start'];
            }

            $days[] = [
                'day_number' => $dayNum,
                'pagi'       => $pagi,
                'kuliner'    => $kuliner,
                'sore'       => $sore,
                'hotel'      => $hotelMalam,
            ];
        }

        // Estimasi biaya makan per hari: Rp 75.000 / hari (ekonomis), Rp 150.000 / hari (standar), Rp 300.000 / hari (mewah)
        $mealCostPerDay = ($budget === 'ekonomis') ? 75000 : (($budget === 'standar') ? 150000 : 300000);
        $totalMealCost  = $mealCostPerDay * $duration;
        $grandTotal     = $totalTicketCost + $totalHotelCost + $totalMealCost;

        return [
            'days'             => $days,
            'duration'         => $duration,
            'budget_tier'      => $budget,
            'total_ticket'     => $totalTicketCost,
            'total_hotel'      => $totalHotelCost,
            'total_meal'       => $totalMealCost,
            'grand_total'      => $grandTotal,
        ];
    }

    private function pickUnused(array $items, array $usedIds): ?array {
        foreach ($items as $item) {
            if (!in_array($item['id'], $usedIds)) {
                return $item;
            }
        }
        return !empty($items) ? $items[array_rand($items)] : null;
    }
}
