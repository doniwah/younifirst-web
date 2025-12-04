<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    
    // Generate reports for the last 7 days
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d H:i:s', strtotime("-$i days"));
        $numReports = rand(1, 5); // 1-5 reports per day
        
        for ($j = 0; $j < $numReports; $j++) {
            $status = (rand(0, 1) == 0) ? 'pending' : 'diproses';
            $stmt = $db->prepare("INSERT INTO reports (user_id, judul, deskripsi, kategori, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                'USR001', // Assuming a user exists
                "Laporan Harian $i-$j",
                "Deskripsi laporan dummy untuk testing grafik",
                "Fasilitas",
                $status,
                $date
            ]);
        }
    }
    
    echo "Seeded dummy reports for the last 7 days.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
