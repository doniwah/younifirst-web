<?php

namespace App\Controller;

use App\App\View;
use App\Repository\DashboardRepository;

class DashboardController
{
    public function index()
    {
        $sessionService = new \App\Service\SessionService();
        $userName = $_SESSION['nama'] ?? 'Admin';
        $userId = $_SESSION['user_id'] ?? null;
        $userRole = $sessionService->getRole();

        $repo = new DashboardRepository();

        if ($userRole === 'admin') {
            try {
                $adminData = [
                    'total_users' => $repo->getTotalUsers(),
                    'active_users' => $repo->getActiveUsers(),
                    'laporan_masuk' => $repo->getLaporanMasukCount(),
                    'call_requests' => $repo->getCallRequestCount(),
                    'chart_data' => $repo->getLaporanMingguan(),
                    'recent_activity' => $repo->getRecentActivity(),
                    'action_items' => $repo->getActionItems(),


                    'laporan_selesai' => $repo->getLaporanSelesaiCount(),
                    'laporan_bulanan' => $repo->getLaporanBulanan(),
                    'top_categories' => $repo->getTopCategories(),
                    'user_growth' => $repo->getUserGrowth(),
                    'average_response_time' => $repo->getAverageResponseTime(),

                    'title' => 'Admin Dashboard - YouniFirst',
                    'user_name' => $userName,
                    'current_date' => date('d F Y')
                ];

                View::render('component/dashboard/admin', $adminData);
            } catch (\Exception $e) {

                $adminData = [
                    'total_users' => 0,
                    'active_users' => 0,
                    'laporan_masuk' => 0,
                    'call_requests' => 0,
                    'chart_data' => $this->getDefaultChartData(),
                    'recent_activity' => $this->getDefaultActivity(),
                    'action_items' => $this->getDefaultActions(),
                    'title' => 'Admin Dashboard - YouniFirst',
                    'user_name' => $userName,
                    'current_date' => date('d F Y')
                ];

                error_log("Dashboard error: " . $e->getMessage());
                View::render('component/dashboard/admin', $adminData);
            }
            return;
        }

        $feedPosts = [];
        $upcomingEvents = [];
        $upcomingCompetitions = [];
        $userForums = [];

        // Try to fetch data from database with individual error handling
        try {
            // ... existing logic ...

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
            usort($allNotifications, function ($a, $b) {
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

    private function getDefaultChartData()
    {
        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $data = [];

        foreach ($days as $day) {
            $data[$day] = [
                'masuk' => rand(5, 20),
                'selesai' => rand(3, 15)
            ];
        }

        return $data;
    }

    private function getDefaultActivity()
    {
        return [
            [
                'icon' => 'bi bi-person-plus',
                'title' => 'Pengguna Baru',
                'desc' => 'Mahasiswa baru terdaftar di sistem',
                'time' => '10 menit lalu',
                'color' => '#4f46e5'
            ],
            [
                'icon' => 'bi bi-file-earmark-check',
                'title' => 'Laporan Diselesaikan',
                'desc' => 'Laporan "Masalah WiFi" telah ditangani',
                'time' => '1 jam lalu',
                'color' => '#10b981'
            ]
        ];
    }

    private function getDefaultActions()
    {
        return [
            [
                'icon' => 'bi bi-exclamation-triangle',
                'title' => 'Laporan Prioritas Tinggi',
                'desc' => 'Review laporan gangguan sistem',
                'tag' => 'high',
                'color' => '#ef4444'
            ],
            [
                'icon' => 'bi bi-clock',
                'title' => 'Call Request Menunggu',
                'desc' => '8 request belum direspon',
                'tag' => 'medium',
                'color' => '#f59e0b'
            ]
        ];
    }
}
