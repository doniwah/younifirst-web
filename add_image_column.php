<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    // Check if column exists
    $checkQuery = "SELECT column_name FROM information_schema.columns 
                   WHERE table_name = 'forum_messages' AND column_name = 'image_url'";
    $stmt = $db->query($checkQuery);
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "Column 'image_url' already exists in forum_messages table.\n";
    } else {
        // Add the column
        $alterQuery = "ALTER TABLE forum_messages ADD COLUMN image_url VARCHAR(255) DEFAULT NULL";
        $db->exec($alterQuery);
        echo "Column 'image_url' added successfully to forum_messages table.\n";
    }
    
    // Verify
    $verifyQuery = "SELECT column_name, data_type, character_maximum_length 
                    FROM information_schema.columns 
                    WHERE table_name = 'forum_messages' AND column_name = 'image_url'";
    $stmt = $db->query($verifyQuery);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "Verification successful:\n";
        echo "  Column: " . $result['column_name'] . "\n";
        echo "  Type: " . $result['data_type'] . "\n";
        echo "  Max Length: " . $result['character_maximum_length'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
