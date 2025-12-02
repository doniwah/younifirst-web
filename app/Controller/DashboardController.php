<?php

namespace App\Controller;

use App\App\View;
use App\Repository\DashboardRepository;

class DashboardController
{
    public function index()
    {
        // Get user session data
        $userName = $_SESSION['nama'] ?? 'Mahasiswa';
        $userId = $_SESSION['user_id'] ?? null;

        // Initialize empty arrays
        $feedPosts = [];
        $upcomingEvents = [];
        $upcomingCompetitions = [];
        $userForums = [];

        // Try to fetch data from database with individual error handling
        try {
            $repo = new DashboardRepository();
            
            // Try to get feed posts
            try {
                $feedPosts = $repo->getFeedPosts(10);
            } catch (\Exception $e) {
                error_log("Error loading feed posts: " . $e->getMessage());
                $feedPosts = [];
            }

            // Try to get upcoming events
            try {
                $upcomingEvents = $repo->getUpcomingEvents(3);
            } catch (\Exception $e) {
                error_log("Error loading upcoming events: " . $e->getMessage());
                $upcomingEvents = [];
            }

            // Try to get upcoming competitions
            try {
                $upcomingCompetitions = $repo->getUpcomingCompetitions(2);
            } catch (\Exception $e) {
                error_log("Error loading upcoming competitions: " . $e->getMessage());
                $upcomingCompetitions = [];
            }

            // Try to get user forums
            try {
                $userForums = $repo->getUserForums($userId);
            } catch (\Exception $e) {
                error_log("Error loading user forums: " . $e->getMessage());
                $userForums = [];
            }

        } catch (\Exception $e) {
            error_log("Error initializing DashboardRepository: " . $e->getMessage());
        }

        View::render('component/dashboard/index', [
            'title' => 'Beranda - YouniFirst',
            'user_name' => $userName,
            'notifications_count' => 0,
            'user_forums' => $userForums,
            'feed_posts' => $feedPosts,
            'upcoming_events' => $upcomingEvents,
            'upcoming_competitions' => $upcomingCompetitions,
            'stat_kompetisi' => 0,
            'stat_lost' => 0,
            'stat_event' => 0,
        ]);
    }
}
