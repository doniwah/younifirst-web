<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    $db->exec("ALTER TABLE forum_komunitas ADD COLUMN IF NOT EXISTS tags TEXT DEFAULT NULL");
    echo "Column 'tags' added successfully to forum_komunitas.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
