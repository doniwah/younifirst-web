<?php
require_once 'app/Config/Database.php';

try {
    $db = \App\Config\Database::getConnection();
    
    echo "Updating statuslostfound enum...\n";
    
    // Try to add 'pending' to the enum
    try {
        // PostgreSQL specific command to add value to enum
        $db->exec("ALTER TYPE statuslostfound ADD VALUE 'pending'");
        echo "✓ Added 'pending' to statuslostfound enum.\n";
    } catch (PDOException $e) {
        // 25P02 is the code for "duplicate object" (value already exists)
        if (strpos($e->getMessage(), '25P02') !== false || strpos($e->getMessage(), 'already exists') !== false) {
             echo "✓ 'pending' already exists in statuslostfound enum.\n";
        } else {
            echo "Error adding 'pending': " . $e->getMessage() . "\n";
        }
    }

    // Also try to add 'waiting' just in case we want to use that
    try {
        $db->exec("ALTER TYPE statuslostfound ADD VALUE 'waiting'");
        echo "✓ Added 'waiting' to statuslostfound enum.\n";
    } catch (PDOException $e) {
         if (strpos($e->getMessage(), '25P02') !== false || strpos($e->getMessage(), 'already exists') !== false) {
             echo "✓ 'waiting' already exists in statuslostfound enum.\n";
        } else {
            echo "Error adding 'waiting': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nEnum update completed.\n";
    
} catch (Exception $e) {
    echo "Critical Error: " . $e->getMessage() . "\n";
}
