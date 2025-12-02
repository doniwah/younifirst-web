<?php

namespace App\Repository;

use App\Config\Database;

class DashboardRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    public function getStatKompetisi()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM lomba WHERE status = 'confirm'")
            ->fetch()['total'];
    }

    public function getStatLost()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM lost_found")
            ->fetch()['total'];
    }

    public function getStatEvent()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM event")
            ->fetch()['total'];
    }

    public function getLatestKompetisi()
    {
        $sql = "
            SELECT nama_lomba, kategori, tanggal_lomba
            FROM lomba
            WHERE status = 'confirm'
            ORDER BY tanggal_lomba ASC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getLatestLost()
    {
        $sql = "
            SELECT nama_barang, lokasi, tanggal, kategori
            FROM lost_found
            ORDER BY tanggal DESC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getLatestEvent()
    {
        $sql = "
            SELECT nama_event, tanggal_mulai, lokasi
            FROM event
            WHERE tanggal_mulai >= CURRENT_DATE
            ORDER BY tanggal_mulai ASC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }

    // New methods for modern dashboard

    public function getNotificationsCount($userId)
    {
        if (!$userId) return 0;
        
        try {
            $sql = "SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = false";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch()['total'] ?? 0;
        } catch (\PDOException $e) {
            return 0; // Return 0 if notifications table doesn't exist
        }
    }

    public function getUserForums($userId, $limit = 3)
    {
        // Dummy data for now - replace with actual forum data when available
        return [
            [
                'name' => 'Kelompok 4',
                'code' => 'AE24',
                'members' => 6,
                'posts' => 25,
                'image' => '/images/forum-placeholder.jpg'
            ],
            [
                'name' => 'Bagi Materi UTS',
                'members' => 908,
                'posts' => 142,
                'image' => '/images/forum-placeholder.jpg'
            ]
        ];
    }

    public function getFeedPosts($limit = 10)
    {
        // Get content from lomba (competitions) with correct column names
        $posts = [];
        
        try {
            $sql = "
                SELECT 
                    lomba_id,
                    nama_lomba,
                    deskripsi,
                    tanggal_lomba,
                    hadiah,
                    kategori,
                    poster_lomba,
                    lokasi,
                    lomba_type,
                    biaya,
                    status
                FROM lomba
                WHERE status = 'confirm'
                ORDER BY tanggal_lomba DESC
                LIMIT ?
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $competitions = $stmt->fetchAll();
            
            foreach ($competitions as $comp) {
                $posts[] = [
                    'type' => 'competition',
                    'user_name' => 'Admin Kompetisi',
                    'user_avatar' => '/images/avatar-default.png',
                    'time_ago' => $this->getTimeAgo($comp['tanggal_lomba']),
                    'title' => $comp['nama_lomba'],
                    'content' => $comp['deskripsi'] ?? '',
                    'image' => $comp['poster_lomba'] ?? '/images/competition-placeholder.jpg',
                    'category' => $comp['kategori'] ?? 'Kompetisi',
                    'prize' => $comp['hadiah'] ?? '',
                    'location' => $comp['lokasi'] ?? '',
                    'type_lomba' => $comp['lomba_type'] ?? '',
                    'fee' => $comp['biaya'] ?? 'Gratis',
                    'date' => date('d M Y', strtotime($comp['tanggal_lomba'])),
                    'status' => $comp['status']
                ];
            }
        } catch (\PDOException $e) {
            error_log("Error fetching feed posts from lomba: " . $e->getMessage());
            // Return empty array on error
        }
        
        return $posts;
    }

    public function getUpcomingEvents($limit = 5)
    {
        try {
            $sql = "
                SELECT 
                    nama_event as title,
                    tanggal_mulai as date,
                    tanggal_selesai as end_date,
                    lokasi as location,
                    kategori,
                    poster_url as image
                FROM event
                WHERE tanggal_mulai >= CURRENT_DATE
                ORDER BY tanggal_mulai ASC
                LIMIT ?
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $events = $stmt->fetchAll();
            
            return array_map(function($event) {
                return [
                    'title' => $event['title'],
                    'date' => date('d M Y', strtotime($event['date'])),
                    'location' => $event['location'] ?? 'TBA',
                    'category' => $event['kategori'] ?? 'Event',
                    'image' => $event['image'] ?? '/images/event-placeholder.jpg'
                ];
            }, $events);
        } catch (\PDOException $e) {
            error_log("Error fetching upcoming events: " . $e->getMessage());
            return [];
        }
    }

    public function getUpcomingCompetitions($limit = 3)
    {
        try {
            $sql = "
                SELECT 
                    lomba_id,
                    nama_lomba,
                    tanggal_lomba,
                    kategori,
                    deskripsi,
                    poster_lomba,
                    hadiah,
                    lokasi,
                    biaya
                FROM lomba
                WHERE status = 'confirm' AND tanggal_lomba >= CURRENT_DATE
                ORDER BY tanggal_lomba ASC
                LIMIT ?
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $competitions = $stmt->fetchAll();
            
            return array_map(function($comp) {
                return [
                    'id' => $comp['lomba_id'],
                    'title' => $comp['nama_lomba'],
                    'deadline' => date('d M Y', strtotime($comp['tanggal_lomba'])),
                    'category' => $comp['kategori'] ?? 'Kompetisi',
                    'description' => $comp['deskripsi'] ?? '',
                    'image' => $comp['poster_lomba'] ?? '/images/competition-placeholder.jpg',
                    'prize' => $comp['hadiah'] ?? '',
                    'location' => $comp['lokasi'] ?? '',
                    'fee' => $comp['biaya'] ?? 'Gratis'
                ];
            }, $competitions);
        } catch (\PDOException $e) {
            error_log("Error fetching upcoming competitions: " . $e->getMessage());
            return [];
        }
    }

    private function getTimeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';
        
        return date('d M Y', $timestamp);
    }
}
