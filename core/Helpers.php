<?php
// core/Helpers.php
// Berisi fungsi-fungsi pembantu (helpers) global untuk website SobatBogor

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount): string {
        if ($amount == 0) return 'Gratis';
        return 'Rp ' . number_format((float)$amount, 0, ',', '.');
    }
}

if (!function_exists('getDestinationStatus')) {
    /**
     * Menghitung status operasional (Buka/Tutup) secara dinamis berdasarkan jam operasional
     * Zona waktu disesuaikan dengan Asia/Jakarta (WIB)
     */
    function getDestinationStatus(?string $openHours): array {
        if (empty($openHours)) {
            return [
                'status' => 'tidak_ada',
                'label'  => 'Jam Buka Tidak Tersedia',
                'detail' => 'Informasi jam buka belum ditambahkan',
                'class'  => 'bg-secondary-subtle text-secondary',
                'style'  => 'background-color: var(--gray-100); color: var(--gray-600);'
            ];
        }

        $clean = strtolower(trim($openHours));

        // Case 1: 24 Jam
        if (strpos($clean, '24 jam') !== false || strpos($clean, '24 hours') !== false || strpos($clean, '24jam') !== false) {
            return [
                'status' => 'buka',
                'label'  => 'Buka Sekarang (24 Jam)',
                'detail' => 'Buka 24 Jam Setiap Hari',
                'class'  => 'bg-success-subtle text-success',
                'style'  => 'background-color: #d1fae5; color: #065f46;'
            ];
        }

        // Normalisasi format jam (misalnya 08.00 -> 08:00)
        $clean = str_replace('.', ':', $clean);

        // Regex untuk mendeteksi range waktu hh:mm - hh:mm
        if (preg_match('/(\d{2}:\d{2})\s*[-–]\s*(\d{2}:\d{2})/', $clean, $matches)) {
            $startTimeStr = $matches[1];
            $endTimeStr   = $matches[2];

            // Simpan timezone lama dan ganti sementara ke Asia/Jakarta (WIB)
            $oldTimezone = date_default_timezone_get();
            date_default_timezone_set('Asia/Jakarta');
            
            $currentTime = date('H:i');
            
            if ($startTimeStr <= $endTimeStr) {
                // Rentang waktu normal (misal 08:00 - 16:00)
                $isOpen = ($currentTime >= $startTimeStr && $currentTime <= $endTimeStr);
            } else {
                // Rentang waktu melewati tengah malam (misal 18:00 - 02:00)
                $isOpen = ($currentTime >= $startTimeStr || $currentTime <= $endTimeStr);
            }

            date_default_timezone_set($oldTimezone);

            if ($isOpen) {
                return [
                    'status' => 'buka',
                    'label'  => 'Buka Sekarang',
                    'detail' => 'Buka s/d ' . $endTimeStr . ' WIB',
                    'class'  => 'bg-success-subtle text-success',
                    'style'  => 'background-color: #d1fae5; color: #065f46;'
                ];
            } else {
                return [
                    'status' => 'tutup',
                    'label'  => 'Tutup Sekarang',
                    'detail' => 'Tutup • Buka Kembali Jam ' . $startTimeStr . ' WIB',
                    'class'  => 'bg-danger-subtle text-danger',
                    'style'  => 'background-color: #fee2e2; color: #991b1b;'
                ];
            }
        }

        // Case fallback jika format jam kustom bebas
        return [
            'status' => 'info',
            'label'  => $openHours,
            'detail' => $openHours,
            'class'  => 'bg-primary-subtle text-primary',
            'style'  => 'background-color: #dbeafe; color: #1e40af;'
        ];
    }
}

if (!function_exists('getDestinationPricing')) {
    /**
     * Mengambil struktur harga (Weekday vs Weekend) secara rapi
     */
    function getDestinationPricing(array $dest): array {
        $weekdayPrice = (float)($dest['ticket_price_weekday'] ?? $dest['ticket_price'] ?? 0);
        $weekendPrice = (float)($dest['ticket_price_weekend'] ?? $dest['ticket_price'] ?? 0);

        // timezone WIB
        $oldTimezone = date_default_timezone_get();
        date_default_timezone_set('Asia/Jakarta');
        $dayOfWeek = (int)date('N'); // 1 (Senin) s/d 7 (Minggu)
        $isWeekend = ($dayOfWeek >= 6); // 6 (Sabtu) atau 7 (Minggu)
        date_default_timezone_set($oldTimezone);

        $todayPrice = $isWeekend ? $weekendPrice : $weekdayPrice;

        return [
            'weekday'      => $weekdayPrice,
            'weekend'      => $weekendPrice,
            'today'        => $todayPrice,
            'is_weekend'   => $isWeekend,
            'today_label'  => $isWeekend ? 'Akhir Pekan (Weekend)' : 'Hari Kerja (Weekday)',
            'formatted_today'   => formatRupiah($todayPrice),
            'formatted_weekday' => formatRupiah($weekdayPrice),
            'formatted_weekend' => formatRupiah($weekendPrice),
        ];
    }
}
