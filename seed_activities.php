<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    
    $logs = [
        ['system', 'System Backup Completed', 'System performed automatic backup'],
        ['login', 'Admin Login', 'Admin user logged in'],
        ['user_register', 'New User Registration', 'User john.doe registered'],
        ['report', 'New Report Submitted', 'Report #123 submitted by user'],
        ['call', 'Call Request Created', 'Urgent call request from student']
    ];

    $stmt = $db->prepare("INSERT INTO activity_logs (action_type, description, notes, created_at) VALUES (?, ?, ?, NOW())");
    
    foreach ($logs as $log) {
        $stmt->execute($log);
    }
    
    echo "Seeded " . count($logs) . " activity logs.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
