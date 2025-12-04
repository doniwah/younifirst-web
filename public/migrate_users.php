<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');

    $columns = [
        'nama_lengkap' => "ADD COLUMN nama_lengkap VARCHAR(255) NULL",
        'angkatan' => "ADD COLUMN angkatan VARCHAR(10) NULL",
        'tgl_lahir' => "ADD COLUMN tgl_lahir DATE NULL",
        'is_notification_active' => "ADD COLUMN is_notification_active TINYINT(1) DEFAULT 1"
    ];

    foreach ($columns as $colName => $sqlPart) {
        try {
            // Check if column exists
            $check = $db->query("SHOW COLUMNS FROM users LIKE '$colName'");
            if ($check->rowCount() == 0) {
                $db->exec("ALTER TABLE users $sqlPart");
                echo "Added column $colName\n";
            } else {
                echo "Column $colName already exists\n";
            }
        } catch (PDOException $e) {
            echo "Error adding $colName: " . $e->getMessage() . "\n";
        }
    }

    echo "Migration completed.\n";

} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
