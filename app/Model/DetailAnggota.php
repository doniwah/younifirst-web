<?php

namespace App\Model;

use PDO;
use PDOException;

class DetailAnggota
{
    private $conn;
    private $table = 'detail_anggota';

    public $team_id;
    public $user_id;
    public $tanggal_gabung;
    public $role;
    public $status;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Create new anggota with waiting status
     */
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (team_id, user_id, tanggal_gabung, role, status) 
                  VALUES 
                  (:team_id, :user_id, NOW(), :role, 'waiting')";

        try {
            $stmt = $this->conn->prepare($query);

            $this->team_id = htmlspecialchars(strip_tags($this->team_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->role = htmlspecialchars(strip_tags($this->role ?? 'anggota'));

            $stmt->bindParam(':team_id', $this->team_id);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':role', $this->role);

            if ($stmt->execute()) {
                error_log("Anggota created successfully: team_id={$this->team_id}, user_id={$this->user_id}");
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("DetailAnggota create error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new anggota with confirmed status (for team creator)
     */
    public function createConfirmed()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (team_id, user_id, tanggal_gabung, role, status) 
                  VALUES 
                  (:team_id, :user_id, NOW(), :role, 'confirm')";

        try {
            $stmt = $this->conn->prepare($query);

            $this->team_id = htmlspecialchars(strip_tags($this->team_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->role = htmlspecialchars(strip_tags($this->role ?? 'ketua'));

            $stmt->bindParam(':team_id', $this->team_id);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':role', $this->role);

            if ($stmt->execute()) {
                error_log("Confirmed anggota created: team_id={$this->team_id}, user_id={$this->user_id}");
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("DetailAnggota createConfirmed error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Read all anggota by team
     */
    public function readByTeam()
    {
        $query = "SELECT da.*, u.username, u.nama, u.email, u.nim, u.jurusan
                  FROM " . $this->table . " da
                  LEFT JOIN users u ON da.user_id = u.user_id
                  WHERE da.team_id = :team_id
                  ORDER BY 
                    CASE da.role WHEN 'ketua' THEN 1 ELSE 2 END,
                    da.tanggal_gabung ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read confirmed anggota only
     */
    public function readConfirmed()
    {
        $query = "SELECT da.*, u.username, u.nama, u.email, u.nim, u.jurusan
                  FROM " . $this->table . " da
                  LEFT JOIN users u ON da.user_id = u.user_id
                  WHERE da.team_id = :team_id AND da.status = 'confirm'
                  ORDER BY 
                    CASE da.role WHEN 'ketua' THEN 1 ELSE 2 END,
                    da.tanggal_gabung ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read pending requests
     */
    public function readPending()
    {
        $query = "SELECT da.*, u.username, u.nama, u.email, u.nim, u.jurusan
                  FROM " . $this->table . " da
                  LEFT JOIN users u ON da.user_id = u.user_id
                  WHERE da.team_id = :team_id AND da.status = 'waiting'
                  ORDER BY da.tanggal_gabung ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Read one specific anggota
     */
    public function readOne()
    {
        $query = "SELECT da.*, u.username, u.nama, u.email, u.nim, u.jurusan
                  FROM " . $this->table . " da
                  LEFT JOIN users u ON da.user_id = u.user_id
                  WHERE da.team_id = :team_id AND da.user_id = :user_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->tanggal_gabung = $row['tanggal_gabung'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    /**
     * Update status (confirm/reject)
     */
    public function updateStatus()
    {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE team_id = :team_id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->team_id = htmlspecialchars(strip_tags($this->team_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->bindParam(':user_id', $this->user_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("DetailAnggota updateStatus error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete anggota
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE team_id = :team_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->bindParam(':user_id', $this->user_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("DetailAnggota delete error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user is already in team
     */
    public function isUserInTeam()
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE team_id = :team_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    /**
     * Check if user is team leader
     */
    public function isTeamLeader()
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE team_id = :team_id AND user_id = :user_id AND role = 'ketua'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    /**
     * Count confirmed members
     */
    public function countConfirmed()
    {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE team_id = :team_id AND status = 'confirm'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }
}
