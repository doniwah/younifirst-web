<?php

namespace App\Repository;

use App\Config\Database;

class CallRequestRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getStats()
    {
        $query = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                    COUNT(CASE WHEN status = 'completed' AND DATE(completed_at) = CURRENT_DATE THEN 1 END) as completed_today
                  FROM call_requests";
        
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return [
            'total' => (int)($result['total'] ?? 0),
            'pending' => (int)($result['pending'] ?? 0),
            'in_progress' => (int)($result['in_progress'] ?? 0),
            'completed' => (int)($result['completed'] ?? 0),
            'completed_today' => (int)($result['completed_today'] ?? 0)
        ];
    }

    public function getAllRequests($status = null)
    {
        $query = "SELECT 
                    cr.*,
                    u.username as user_username,
                    u.nama_lengkap as user_name,
                    u.email as user_email,
                    a.username as admin_username,
                    a.nama_lengkap as admin_name
                  FROM call_requests cr
                  LEFT JOIN users u ON cr.user_id = u.user_id
                  LEFT JOIN users a ON cr.admin_id = a.user_id";
        
        if ($status) {
            $query .= " WHERE cr.status = :status";
        }
        
        $query .= " ORDER BY 
                    CASE cr.priority 
                        WHEN 'urgent' THEN 1 
                        WHEN 'medium' THEN 2 
                        WHEN 'low' THEN 3 
                    END,
                    cr.created_at DESC";
        
        $statement = $this->connection->prepare($query);
        
        if ($status) {
            $statement->execute(['status' => $status]);
        } else {
            $statement->execute();
        }
        
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $requests = [];
        foreach ($results as $row) {
            $requests[] = $this->formatRequest($row);
        }

        return $requests;
    }

    private function formatRequest($row)
    {
        return [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'user_name' => $row['user_name'] ?: $row['user_username'],
            'user_email' => $row['user_email'],
            'subject' => $row['subject'],
            'description' => $row['description'],
            'priority' => $row['priority'],
            'status' => $row['status'],
            'admin_id' => $row['admin_id'],
            'admin_name' => $row['admin_name'] ?: $row['admin_username'],
            'notes' => $row['notes'],
            'created_at' => $this->formatDate($row['created_at']),
            'completed_at' => $row['completed_at'] ? $this->formatDate($row['completed_at']) : null
        ];
    }

    private function formatDate($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam lalu';
        } else {
            return date('d M Y, H:i', $time);
        }
    }

    public function updateStatus($requestId, $status, $adminId = null, $notes = null)
    {
        $query = "UPDATE call_requests 
                  SET status = :status,
                      admin_id = :admin_id,
                      notes = :notes,
                      updated_at = NOW()";
        
        if ($status === 'completed') {
            $query .= ", completed_at = NOW()";
        }
        
        $query .= " WHERE id = :id";
        
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'id' => $requestId,
            'status' => $status,
            'admin_id' => $adminId,
            'notes' => $notes
        ]);

        return true;
    }

    public function createRequest($data)
    {
        $query = "INSERT INTO call_requests (user_id, subject, description, priority) 
                  VALUES (:user_id, :subject, :description, :priority)";
        
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'user_id' => $data['user_id'],
            'subject' => $data['subject'],
            'description' => $data['description'],
            'priority' => $data['priority'] ?? 'medium'
        ]);

        return $this->connection->lastInsertId();
    }
}