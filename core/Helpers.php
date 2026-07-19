<?php
// core/Helpers.php
// Berisi fungsi-fungsi pembantu (helpers) global untuk website SobatBogor

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
                'class'  => 'bg-success-subtle text-success',
                'style'  => 'background-color: #d1fae5; color: #065f46;'
            ];
        }

        // Normalisasi format jam (misalnya 08.00 -> 08:00)
        $clean = str_replace('.', ':', $clean);

        // Regex untuk mendeteksi range waktu hh:mm - hh:mm
        if (preg_match('/(\d{2}:\d{2})\s*[-–]\s*(\d{2}:\d{2})/', $clean, $matches)) {
            $startTimeStr = $matches[1];
            $endTimeStr = $matches[2];

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
                    'class'  => 'bg-success-subtle text-success',
                    'style'  => 'background-color: #d1fae5; color: #065f46;'
                ];
            } else {
                return [
                    'status' => 'tutup',
                    'label'  => 'Tutup',
                    'class'  => 'bg-danger-subtle text-danger',
                    'style'  => 'background-color: #fee2e2; color: #991b1b;'
                ];
            }
        }

        // Case fallback jika format jam kustom bebas
        return [
            'status' => 'info',
            'label'  => $openHours,
            'class'  => 'bg-primary-subtle text-primary',
            'style'  => 'background-color: #dbeafe; color: #1e40af;'
        ];
    }
}
