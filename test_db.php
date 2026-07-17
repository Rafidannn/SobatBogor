<?php
define('ROOT_PATH', __DIR__);

require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Query untuk mengambil nama database aktif
    $stmt = $db->query("SELECT DATABASE() AS db_name");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "--- STATUS KONEKSI DATABASE ---\n";
    echo "Status   : Berhasil Terhubung! ✅\n";
    echo "Database : " . ($row['db_name'] ?? 'NULL') . "\n";
    echo "-------------------------------\n";
} catch (Exception $e) {
    echo "--- STATUS KONEKSI DATABASE ---\n";
    echo "Status   : Gagal Terhubung! ❌\n";
    echo "Error    : " . $e->getMessage() . "\n";
    echo "-------------------------------\n";
}
