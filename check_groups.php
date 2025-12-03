<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    $stmt = $db->query("SELECT * FROM forum_groups");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total Groups: " . count($groups) . "\n";
    print_r($groups);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
