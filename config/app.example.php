<?php
/**
 * config/app.php
 * =====================================================
 * SETUP INSTRUCTIONS (untuk anggota tim baru):
 * 1. Copy file ini: salin `app.example.php` → `app.php`
 * 2. Sesuaikan nilai 'url' dengan URL lokal kamu
 *    - Laragon default: http://sobatbogor.test
 *    - XAMPP default:   http://localhost/sobatbogor/public
 * 3. Isi API keys jika diperlukan (Tugas 5)
 * =====================================================
 */
return [
    'name'        => 'SobatBogor',
    'url'         => 'http://sobatbogor.test',   // <-- GANTI SESUAI URL LOKAL KAMU
    'env'         => 'development',              // 'development' atau 'production'
    'debug'       => true,
    'timezone'    => 'Asia/Jakarta',

    // Third-party API Keys (isi sebelum mengerjakan Tugas 5)
    'gmaps_key'   => '',   // Google Maps API Key
    'weather_key' => '',   // OpenWeatherMap API Key
];
