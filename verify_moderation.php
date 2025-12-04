<?php
require_once 'app/Config/Database.php';
require_once 'app/Repository/ModerationRepository.php';

use App\Repository\ModerationRepository;

try {
    $db = \App\Config\Database::getConnection();
    $repo = new ModerationRepository();

    // 1. Seed some pending items
    echo "Seeding pending items...\n";
    
    // Event
    $db->exec("INSERT INTO events (title, description, status, created_at) VALUES ('Pending Event', 'Test Description', 'pending', NOW())");
    $eventId = $db->lastInsertId();
    
    // Lost & Found
    $db->exec("INSERT INTO lost_found (nama_barang, deskripsi, status, tanggal) VALUES ('Pending Item', 'Test Description', 'pending', NOW())");
    $lostId = $db->lastInsertId();

    // 2. Fetch pending items
    echo "Fetching pending items...\n";
    $items = $repo->getPendingItems();
    $foundEvent = false;
    $foundLost = false;

    foreach ($items as $item) {
        if ($item['type'] == 'event' && $item['id'] == $eventId) $foundEvent = true;
        if ($item['type'] == 'lost_found' && $item['id'] == $lostId) $foundLost = true;
        echo "- Found: {$item['title']} ({$item['type']})\n";
    }

    if ($foundEvent && $foundLost) {
        echo "SUCCESS: Pending items found.\n";
    } else {
        echo "FAILURE: Pending items not found.\n";
    }

    // 3. Test Update Status
    echo "Testing status update...\n";
    $repo->updateStatus('event', $eventId, 'approved');
    $repo->updateStatus('lost_found', $lostId, 'rejected');

    // Verify updates
    $stmt = $db->prepare("SELECT status FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $eventStatus = $stmt->fetchColumn();
    echo "Event status: $eventStatus (Expected: approved)\n";

    $stmt = $db->prepare("SELECT status FROM lost_found WHERE id = ?");
    $stmt->execute([$lostId]);
    $lostStatus = $stmt->fetchColumn();
    echo "Lost Found status: $lostStatus (Expected: rejected)\n";

    // Cleanup
    $db->exec("DELETE FROM events WHERE id = $eventId");
    $db->exec("DELETE FROM lost_found WHERE id = $lostId");

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
