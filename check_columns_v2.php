<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    $tables = ['team', 'lomba'];
    
    foreach ($tables as $table) {
        echo "Table: $table\n";
        try {
            $stmt = $db->query("SELECT column_name FROM information_schema.columns WHERE table_name = '$table'");
            $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
            print_r($cols);
        } catch (Exception $e) {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
