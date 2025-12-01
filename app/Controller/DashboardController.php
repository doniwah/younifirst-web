<?php

namespace App\Controller;

use App\App\View;
use App\Repository\DashboardRepository;

class DashboardController
{
    public function index()
    {
        $repo = new DashboardRepository();
        
        // Get user session data (session already started by SessionService)
        $userId = $_SESSION['user_id'] ?? null;
        $userName = $_SESSION['nama'] ?? 'Mahasiswa';

        View::render('component/dashboard/index', [
            'title' => 'Beranda - YouniFirst',
            'user_name' => $userName,
            'notifications_count' => $repo->getNotificationsCount($userId),
            'user_forums' => $repo->getUserForums($userId),
            'feed_posts' => $repo->getFeedPosts(5),
            'upcoming_events' => $repo->getUpcomingEvents(3),
            'upcoming_competitions' => $repo->getUpcomingCompetitions(2),
            // Keep old data for backward compatibility
            'stat_kompetisi'   => $repo->getStatKompetisi(),
            'stat_lost'        => $repo->getStatLost(),
            'stat_event'       => $repo->getStatEvent(),
        ]);
    }
}
