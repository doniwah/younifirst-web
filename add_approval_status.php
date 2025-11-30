<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    echo "Connected to database.\n";

    // Add approval_status column to teams table
    $db->exec("ALTER TABLE teams ADD COLUMN IF NOT EXISTS approval_status VARCHAR(20) DEFAULT NULL");
    echo "Added approval_status column to teams table.\n";

    echo "\nColumn added successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
