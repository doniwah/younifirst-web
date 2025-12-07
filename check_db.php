<?php
require_once __DIR__ . '/app/Config/Database.php';

try {
    $pdo = \App\Config\Database::getConnection('test');
    echo "Connection successful!\n";
} catch (\Throwable $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
