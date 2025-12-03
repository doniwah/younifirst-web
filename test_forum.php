<?php
// Test script to check Forum methods
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Model/Forum.php';

use App\Model\Forum;

try {
    echo "Testing Forum methods...\n\n";
    
    // Test getKomunitas
    echo "1. Testing getKomunitas()... ";
    $komunitas = Forum::getKomunitas();
    echo "OK (" . count($komunitas) . " results)\n";
    
    // Test getKomunitasById
    echo "2. Testing getKomunitasById(14)... ";
    $k = Forum::getKomunitasById(14);
    echo $k ? "OK\n" : "NOT FOUND\n";
    
    // Test getGroups
    echo "3. Testing getGroups(14)... ";
    $groups = Forum::getGroups(14);
    echo "OK (" . count($groups) . " results)\n";
    
    // Test getMessages
    echo "4. Testing getMessages(14, 2)... ";
    $messages = Forum::getMessages(14, 2);
    echo "OK (" . count($messages) . " results)\n";
    
    echo "\nAll tests passed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
