<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class DetailAnggotaRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    /**
     * Add anggota to team with default status 'waiting'
     * @param int $teamId
     * @param int $userId
     * @param string $role Default 'anggota'
     * @return bool
     */
    public function addAnggota($teamId, $userId, $role = 'anggota')
    {
        $sql = "
            INSERT INTO detail_anggota (team_id, user_id, tanggal_gabung, role, status)
            VALUES (?, NOW(), ?, 'waiting')
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$teamId, $userId, $role]);
        } catch (\PDOException $e) {
            error_log("Error adding anggota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add anggota with confirmed status (for team creator)
     * @param int $teamId
     * @param int $userId
     * @param string $role
     * @return bool
     */
    public function addAnggotaConfirmed($teamId, $userId, $role = 'ketua')
    {
        $sql = "
            INSERT INTO detail_anggota (team_id, user_id, tanggal_gabung, role, status)
            VALUES (?, NOW(), ?, 'confirm')
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$teamId, $userId, $role]);
        } catch (\PDOException $e) {
            error_log("Error adding confirmed anggota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all anggota by team
     * @param int $teamId
     * @return array
     */
    public function getAnggotaByTeam($teamId)
    {
        $sql = "
            SELECT 
                da.*,
                u.username,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM detail_anggota da
            LEFT JOIN users u ON da.user_id = u.user_id
            WHERE da.team_id = ?
            ORDER BY 
                CASE da.role 
                    WHEN 'ketua' THEN 1 
                    ELSE 2 
                END,
                da.tanggal_gabung ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get confirmed anggota only
     * @param int $teamId
     * @return array
     */
    public function getConfirmedAnggota($teamId)
    {
        $sql = "
            SELECT 
                da.*,
                u.username,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM detail_anggota da
            LEFT JOIN users u ON da.user_id = u.user_id
            WHERE da.team_id = ? AND da.status = 'confirm'
            ORDER BY 
                CASE da.role 
                    WHEN 'ketua' THEN 1 
                    ELSE 2 
                END,
                da.tanggal_gabung ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get pending join requests
     * @param int $teamId
     * @return array
     */
    public function getPendingRequests($teamId)
    {
        $sql = "
            SELECT 
                da.*,
                u.username,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM detail_anggota da
            LEFT JOIN users u ON da.user_id = u.user_id
            WHERE da.team_id = ? AND da.status = 'waiting'
            ORDER BY da.tanggal_gabung ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get specific anggota by team and user
     * @param int $teamId
     * @param int $userId
     * @return array|false
     */
    public function getAnggotaByTeamAndUser($teamId, $userId)
    {
        $sql = "
            SELECT 
                da.*,
                u.username,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM detail_anggota da
            LEFT JOIN users u ON da.user_id = u.user_id
            WHERE da.team_id = ? AND da.user_id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update status anggota (confirm/reject)
     * @param int $teamId
     * @param int $userId
     * @param string $status 'confirm' or 'rejected'
     * @return bool
     */
    public function updateStatusAnggota($teamId, $userId, $status)
    {
        $sql = "
            UPDATE detail_anggota 
            SET status = ?
            WHERE team_id = ? AND user_id = ?
        ";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $teamId, $userId]);
        } catch (\PDOException $e) {
            error_log("Error updating anggota status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove anggota from team
     * @param int $teamId
     * @param int $userId
     * @return bool
     */
    public function removeAnggota($teamId, $userId)
    {
        $sql = "DELETE FROM detail_anggota WHERE team_id = ? AND user_id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$teamId, $userId]);
        } catch (\PDOException $e) {
            error_log("Error removing anggota: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Count confirmed anggota
     * @param int $teamId
     * @return int
     */
    public function countConfirmedAnggota($teamId)
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM detail_anggota 
            WHERE team_id = ? AND status = 'confirm'
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    /**
     * Count pending requests
     * @param int $teamId
     * @return int
     */
    public function countPendingRequests($teamId)
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM detail_anggota 
            WHERE team_id = ? AND status = 'waiting'
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    /**
     * Check if user is already in team (any status)
     * @param int $teamId
     * @param int $userId
     * @return bool
     */
    public function isUserInTeam($teamId, $userId)
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM detail_anggota 
            WHERE team_id = ? AND user_id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Get team leader (ketua)
     * @param int $teamId
     * @return array|false
     */
    public function getTeamLeader($teamId)
    {
        $sql = "
            SELECT 
                da.*,
                u.username,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM detail_anggota da
            LEFT JOIN users u ON da.user_id = u.user_id
            WHERE da.team_id = ? AND da.role = 'ketua'
            LIMIT 1
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if user is team leader
     * @param int $teamId
     * @param int $userId
     * @return bool
     */
    public function isTeamLeader($teamId, $userId)
    {
        $sql = "
            SELECT COUNT(*) as total 
            FROM detail_anggota 
            WHERE team_id = ? AND user_id = ? AND role = 'ketua'
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
}
