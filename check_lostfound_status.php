<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    
    echo "Checking lost_found status values...\n";
    
    // 1. Try to get distinct values from the table
    try {
        $stmt = $db->query("SELECT DISTINCT status FROM lost_found");
        $statuses = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Existing statuses in table: " . implode(', ', $statuses) . "\n";
    } catch (Exception $e) {
        echo "Error querying distinct status: " . $e->getMessage() . "\n";
    }

    // 2. Try to get enum values directly
    try {
        $stmt = $db->query("SELECT unnest(enum_range(NULL::statuslostfound))");
        $enumValues = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Enum values (statuslostfound): " . implode(', ', $enumValues) . "\n";
    } catch (Exception $e) {
        echo "Error querying enum range: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Critical Error: " . $e->getMessage() . "\n";
}
