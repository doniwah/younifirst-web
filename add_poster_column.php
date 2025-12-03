<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    echo "Connected to database.\n";

    // Add poster_lomba column to teams table
    $sql = "ALTER TABLE team ADD COLUMN IF NOT EXISTS poster_lomba VARCHAR(255) DEFAULT NULL";
    $db->exec($sql);
    echo "Added poster_lomba column to teams table.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
