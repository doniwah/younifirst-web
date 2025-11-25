<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Repository\Dummy\UserDummyRepository;
use App\Repository\Dummy\TeamDummyRepository;
use App\Repository\Dummy\LombaDummyRepository;
use App\Repository\Dummy\EventDummyRepository;
use App\Repository\Dummy\LostFoundDummyRepository;
use App\Repository\Dummy\ForumDummyRepository;

echo "<h3>Generate All Dummy (Supabase/Postgres)</h3>";

try {
    // get PDO from your existing Database config (use 'prod' as your production DB)
    $db = Database::getConnection('prod');

    // 1) users
    echo "Generating users...<br>";
    $userRepo = new UserDummyRepository($db);
    $userIds = $userRepo->generateUsers(50); // create 50 users
    echo "Generated " . count($userIds) . " users.<br>";

    // 2) teams + detail_anggota
    echo "Generating teams...<br>";
    $teamRepo = new TeamDummyRepository($db);
    $teamIds = $teamRepo->generateTeams($userIds, 15, 4);
    echo "Generated " . count($teamIds) . " teams.<br>";

    // 3) lomba
    echo "Generating lomba...<br>";
    $lombaRepo = new LombaDummyRepository($db);
    $lombaRepo->generateLomba($userIds, 30);
    echo "Generated lomba entries.<br>";

    // 4) events
    echo "Generating events...<br>";
    $eventRepo = new EventDummyRepository($db);
    $eventIds = $eventRepo->generateEvents(12);
    echo "Generated " . count($eventIds) . " events.<br>";

    // 5) lost & found
    echo "Generating lost & found...<br>";
    $lostRepo = new LostFoundDummyRepository($db);
    $lostIds = $lostRepo->generateLostFound($userIds, 20);
    echo "Generated " . count($lostIds) . " lost&found items.<br>";

    // 6) forum
    echo "Generating forum komunitas, anggota & messages...<br>";
    $forumRepo = new ForumDummyRepository($db);
    $forumRepo->generateForum($userIds, 6, 6);
    echo "Forum data generated.<br>";

    echo "<br><strong>ALL DONE! ✅</strong>";
} catch (\Exception $e) {
    echo "<b>Error:</b> " . $e->getMessage();
}
