<?php
require_once 'app/Config/Database.php';
require_once 'app/Repository/ModerationRepository.php';

use App\Repository\ModerationRepository;

try {
    $repo = new ModerationRepository();
    echo "Calling getPendingItems()...\n";
    $items = $repo->getPendingItems();
    echo "Success! Found " . count($items) . " items.\n";
    foreach ($items as $item) {
        echo "- [{$item['type']}] {$item['title']} (Status: " . ($item['status'] ?? 'unknown') . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
