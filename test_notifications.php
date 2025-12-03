<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    echo "Creating dummy data for notifications...\n";

    // 1. Create a dummy Event
    // Generate random event_id
    $eventId = rand(10000, 99999);
    
    $stmt = $db->prepare("INSERT INTO event (event_id, nama_event, deskripsi, tanggal_mulai, tanggal_selsai, lokasi, organizer, kapasitas, status, poster_event, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $eventId,
        'Seminar Nasional Teknologi 2025',
        'Seminar tentang perkembangan AI di Indonesia',
        date('Y-m-d', strtotime('+1 week')),
        date('Y-m-d', strtotime('+1 week +2 hours')),
        'Aula Utama',
        'BEM',
        100,
        'confirm',
        '/images/event-placeholder.jpg'
    ]);
    echo "Created dummy Event.\n";

    // 2. Create a dummy Lost & Found item
    // Need a valid user_id first
    $userStmt = $db->query("SELECT user_id FROM users LIMIT 1");
    $user = $userStmt->fetch();
    $userId = $user['user_id'] ?? 1;

    // Generate random id_barang
    $idBarang = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

    $stmt = $db->prepare("INSERT INTO lost_found (id_barang, user_id, nama_barang, deskripsi, kategori, lokasi, no_hp, email, tanggal, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([
        $idBarang,
        $userId,
        'Dompet Kulit Hitam',
        'Hilang di sekitar kantin, berisi KTP dan KTM',
        'hilang',
        'Kantin',
        '08123456789',
        'test@example.com',
        'open'
    ]);
    echo "Created dummy Lost & Found item.\n";

    echo "Done! Please check your dashboard notifications.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
