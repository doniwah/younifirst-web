<?php

namespace App\Controller;

use App\App\View;
use App\Repository\DashboardRepository;

class DashboardController
{
    public function index()
    {
        try {
            $repo = new DashboardRepository();
            
            // Get user session data
            $userName = $_SESSION['nama'] ?? 'Mahasiswa';
            $userId = $_SESSION['user_id'] ?? null;

            // Fetch data from database
            $feedPosts = $repo->getFeedPosts(10);
            $upcomingEvents = $repo->getUpcomingEvents(3);
            $upcomingCompetitions = $repo->getUpcomingCompetitions(2);

            View::render('component/dashboard/index', [
                'title' => 'Beranda - YouniFirst',
                'user_name' => $userName,
                'notifications_count' => 0,
                'user_forums' => $repo->getUserForums($userId),
                'feed_posts' => $feedPosts,
                'upcoming_events' => $upcomingEvents,
                'upcoming_competitions' => $upcomingCompetitions,
                'stat_kompetisi' => 0,
                'stat_lost' => 0,
                'stat_event' => 0,
            ]);
        } catch (\Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());
            echo "<h1>Error loading dashboard</h1>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
    }
}
