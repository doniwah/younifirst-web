<?php

namespace App\Repository;

use App\Config\Database;

class EventRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    /**
     * Get all events with role-based filtering
     * Admin sees all events, regular users only see confirmed events
     */
    public function getAllEvents($userRole = 'user')
    {
        $statusCondition = ($userRole === 'admin') 
            ? "" 
            : "WHERE status = 'confirm'";
        
        $sql = "
            SELECT 
                event_id,
                nama_event,
                deskripsi,
                tanggal_mulai,
                tanggal_selsai,
                lokasi,
                organizer,
                kapasitas,
                peserta_terdaftar,
                status,
                poster_event,
                created_at
            FROM event
            $statusCondition
            ORDER BY tanggal_mulai DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get event by ID
     */
    public function getEventById($id)
    {
        $sql = "SELECT * FROM event WHERE event_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new event
     * Status is 'waiting' by default, will be 'confirm' if created by admin
     */
    public function createEvent($data)
    {
        $sql = "
            INSERT INTO event 
            (nama_event, deskripsi, tanggal_mulai, tanggal_selsai, lokasi, organizer, kapasitas, poster_event, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['nama_event'],
            $data['deskripsi'],
            $data['tanggal_mulai'],
            $data['tanggal_selsai'],
            $data['lokasi'],
            $data['organizer'],
            $data['kapasitas'],
            $data['poster_event'] ?? null,
            $data['status'] ?? 'waiting'
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update event
     */
    public function updateEvent($id, $data)
    {
        $fields = [];
        $params = [];
        
        $allowedFields = [
            'nama_event', 'deskripsi', 'tanggal_mulai', 'tanggal_selsai',
            'lokasi', 'organizer', 'kapasitas', 'poster_event', 'status'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE event SET " . implode(', ', $fields) . " WHERE event_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete event
     */
    public function deleteEvent($id)
    {
        $sql = "DELETE FROM event WHERE event_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Confirm event (change status from waiting to confirm)
     */
    public function confirmEvent($id)
    {
        $sql = "UPDATE event SET status = 'confirm' WHERE event_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Register user for event
     */
    public function registerForEvent($eventId, $userId)
    {
        try {
            // Check if already registered
            $checkSql = "SELECT id FROM pendaftaran_event WHERE event_id = ? AND user_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$eventId, $userId]);
            
            if ($checkStmt->fetch()) {
                return false; // Already registered
            }
            
            // Check capacity
            $event = $this->getEventById($eventId);
            if ($event['peserta_terdaftar'] >= $event['kapasitas']) {
                return false; // Event is full
            }
            
            // Register user
            $sql = "INSERT INTO pendaftaran_event (event_id, user_id, status) VALUES (?, ?, 'registered')";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$eventId, $userId]);
            
            if ($result) {
                // Increment peserta_terdaftar
                $updateSql = "UPDATE event SET peserta_terdaftar = peserta_terdaftar + 1 WHERE event_id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->execute([$eventId]);
            }
            
            return $result;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Get event registrations
     */
    public function getEventRegistrations($eventId)
    {
        $sql = "
            SELECT 
                pe.*,
                u.username,
                u.email,
                u.nama
            FROM pendaftaran_event pe
            LEFT JOIN users u ON pe.user_id = u.user_id
            WHERE pe.event_id = ?
            ORDER BY pe.tanggal_daftar DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if user is registered for event
     */
    public function isUserRegistered($eventId, $userId)
    {
        $sql = "SELECT id FROM pendaftaran_event WHERE event_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId, $userId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Count events by status
     */
    public function countEventsByStatus($status)
    {
        $sql = "SELECT COUNT(*) as total FROM event WHERE status = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetch()['total'];
    }

    /**
     * Get upcoming events (confirmed only)
     */
    public function getUpcomingEvents($limit = 5)
    {
        $sql = "
            SELECT * FROM event 
            WHERE status = 'confirm' AND tanggal_mulai > NOW()
            ORDER BY tanggal_mulai ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}