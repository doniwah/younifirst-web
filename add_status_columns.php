<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    
    // Add status column to events if not exists
    try {
        $db->exec("ALTER TABLE events ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
        echo "Added status column to events table.\n";
    } catch (PDOException $e) {
        // Ignore if column already exists (SQLSTATE 42701 for Duplicate column name in some DBs, or just check message)
        echo "Events table: " . $e->getMessage() . "\n";
    }

    // Add status column to lost_found if not exists
    try {
        $db->exec("ALTER TABLE lost_found ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
        echo "Added status column to lost_found table.\n";
    } catch (PDOException $e) {
        echo "Lost_found table: " . $e->getMessage() . "\n";
    }
    
    // Ensure competitions has status column
    try {
        $db->exec("ALTER TABLE competitions ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
        echo "Added status column to competitions table.\n";
    } catch (PDOException $e) {
        echo "Competitions table: " . $e->getMessage() . "\n";
    }

     // Ensure teams has status column
     try {
        $db->exec("ALTER TABLE teams ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
        echo "Added status column to teams table.\n";
    } catch (PDOException $e) {
        echo "Teams table: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
