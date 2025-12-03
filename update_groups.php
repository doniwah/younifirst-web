<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    $sql = file_get_contents('update_forum_groups.sql');
    $db->exec($sql);
    echo "Groups Schema updated successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
