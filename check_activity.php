<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    $stmt = $db->query('SELECT COUNT(*) FROM activity_logs');
    echo 'Activity Logs Count: ' . $stmt->fetchColumn() . "\n";
    
    // Also check if there are any logs
    $stmt = $db->query('SELECT * FROM activity_logs LIMIT 1');
    $log = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($log) {
        print_r($log);
    } else {
        echo "No logs found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
