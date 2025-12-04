<?php
require_once 'app/Config/Database.php';
require_once 'app/Repository/DashboardRepository.php';

use App\Repository\DashboardRepository;

try {
    $repo = new DashboardRepository();
    
    echo "Testing getLaporanMingguan()...\n";
    $weeklyReports = $repo->getLaporanMingguan();
    print_r($weeklyReports);
    
    echo "\nTesting getRecentActivity()...\n";
    $recentActivity = $repo->getRecentActivity();
    print_r($recentActivity);

    echo "\nTesting getActionItems()...\n";
    $actionItems = $repo->getActionItems();
    print_r($actionItems);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
