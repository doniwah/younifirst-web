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

            // Get recent events for notifications
            $recentEvents = [];
            try {
                $recentEvents = $repo->getRecentEventsForNotification(3);
                // Add type and format for notification
                foreach ($recentEvents as &$event) {
                    $event['type'] = 'event';
                    $event['name'] = $event['nama_event'];
                    $event['image'] = $event['poster_event'] ?? '/images/event-placeholder.jpg';
                    $event['created_at'] = $event['created_at'] ?? date('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                error_log("Error loading recent events: " . $e->getMessage());
            }

            // Get recent lost & found for notifications
            $recentLostFound = [];
            try {
                $recentLostFound = $repo->getRecentLostFoundForNotification(3);
                // Add type and format for notification
                foreach ($recentLostFound as &$item) {
                    $item['type'] = 'lost_found';
                    $item['name'] = $item['nama_barang'];
                    $item['image'] = '/images/lost-found-placeholder.jpg'; // Placeholder or specific image
                    $item['created_at'] = $item['tanggal'] ?? date('Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                error_log("Error loading recent lost & found: " . $e->getMessage());
            }

            // Merge all notifications
            $allNotifications = array_merge($userForums, $recentEvents, $recentLostFound);
            
            // Sort by created_at desc
            usort($allNotifications, function($a, $b) {
                return strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now');
            });

            // Limit to 5 most recent
            $newNotifications = array_slice($allNotifications, 0, 5);

            // Calculate unread count based on cookie
            $lastCheck = isset($_COOKIE['last_notif_check']) ? strtotime($_COOKIE['last_notif_check']) : 0;
            $unreadCount = 0;
            foreach ($allNotifications as $notif) {
                $notifTime = strtotime($notif['created_at'] ?? 'now');
                if ($notifTime > $lastCheck) {
                    $unreadCount++;
                }
            }

        } catch (\Exception $e) {
            error_log("Error initializing DashboardRepository: " . $e->getMessage());
            $newNotifications = [];
            $unreadCount = 0;
        }

        View::render('component/dashboard/index', [
            'title' => 'Beranda - YouniFirst',
            'user_name' => $userName,
            'notifications_count' => $unreadCount,
            'notifications_count' => $unreadCount,
            'user_forums' => $userForums, // Pass actual user forums for sidebar
            'new_notifications' => $newNotifications, // Pass notifications for header
            'feed_posts' => $feedPosts,
            'upcoming_events' => $upcomingEvents,
            'upcoming_competitions' => $upcomingCompetitions,
            'stat_kompetisi' => 0,
            'stat_lost' => 0,
            'stat_event' => 0,
        ]);
    }
}
