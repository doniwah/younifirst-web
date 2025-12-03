<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    $sql = file_get_contents('update_forum_schema.sql');
    $db->exec($sql);
    echo "Schema updated successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
