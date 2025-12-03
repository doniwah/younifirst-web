<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    $stmt = $db->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'forum_komunitas'");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in forum_komunitas:\n";
    foreach ($columns as $col) {
        echo "- " . $col['column_name'] . " (" . $col['data_type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
