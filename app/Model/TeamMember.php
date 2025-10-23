<?php

namespace App\Models;

use PDO;
use PDOException;

class TeamMember
{
    private $conn;
    private $table = 'detail_angota';

    public $detail_id;
    public $tanggal_gabung;
    public $role;
    public $status;
    public $team_id;
    public $user_id;

    // Additional fields for join request
    public $alasan_bergabung;
    public $keahlian_pengalaman;
    public $portfolio_link;
    public $kontak;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }
    public function createRequest()
    {
        $query = "INSERT INTO " . $this->table . " 
              (tanggal_gabung, role, status, team_id, user_id) 
              VALUES 
              (NOW(), :role, 'waiting', :team_id, :user_id)";

        try {
            $stmt = $this->conn->prepare($query);

            $this->role = htmlspecialchars(strip_tags($this->role));
            $this->team_id = htmlspecialchars(strip_tags($this->team_id));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':team_id', $this->team_id);
            $stmt->bindParam(':user_id', $this->user_id);

            if ($stmt->execute()) {
                $this->detail_id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team member request error: " . $e->getMessage());
            return false;
        }
    }

    public function readByTeam($team_id, $status = null)
    {
        if ($status) {
            $query = "SELECT da.*, u.username, u.email 
                      FROM " . $this->table . " da 
                      LEFT JOIN users u ON da.user_id = u.user_id 
                      WHERE da.team_id = :team_id AND da.status = :status 
                      ORDER BY da.tanggal_gabung DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':team_id', $team_id);
            $stmt->bindParam(':status', $status);
        } else {
            $query = "SELECT da.*, u.username, u.email 
                      FROM " . $this->table . " da 
                      LEFT JOIN users u ON da.user_id = u.user_id 
                      WHERE da.team_id = :team_id 
                      ORDER BY da.tanggal_gabung DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':team_id', $team_id);
        }

        $stmt->execute();
        return $stmt;
    }

    public function readPendingByTeamOwner($owner_user_id)
    {
        $query = "SELECT da.*, t.nama_team, u.username, u.email 
                  FROM " . $this->table . " da 
                  LEFT JOIN team t ON da.team_id = t.team_id 
                  LEFT JOIN users u ON da.user_id = u.user_id 
                  WHERE da.status = 'waiting' 
                  ORDER BY da.tanggal_gabung DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByUser($user_id)
    {
        $query = "SELECT da.*, t.nama_team 
                  FROM " . $this->table . " da 
                  LEFT JOIN team t ON da.team_id = t.team_id 
                  WHERE da.user_id = :user_id 
                  ORDER BY da.tanggal_gabung DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT da.*, t.nama_team, u.username, u.email 
                  FROM " . $this->table . " da 
                  LEFT JOIN team t ON da.team_id = t.team_id 
                  LEFT JOIN users u ON da.user_id = u.user_id 
                  WHERE da.detail_id = :detail_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':detail_id', $this->detail_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->tanggal_gabung = $row['tanggal_gabung'];
            $this->role = $row['role'];
            $this->status = $row['status'];
            $this->team_id = $row['team_id'];
            $this->user_id = $row['user_id'];
            return true;
        }
        return false;
    }

    public function approve()
    {
        $query = "UPDATE " . $this->table . " SET status = 'confirm' WHERE detail_id = :detail_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':detail_id', $this->detail_id);

        try {
            if ($stmt->execute()) {
                // Update team member count
                $this->updateTeamMemberCount();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team member approve error: " . $e->getMessage());
            return false;
        }
    }

    public function reject()
    {
        $query = "DELETE FROM " . $this->table . " WHERE detail_id = :detail_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':detail_id', $this->detail_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team member reject error: " . $e->getMessage());
            return false;
        }
    }

    private function updateTeamMemberCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE team_id = :team_id AND status = 'confirm'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'];

        $updateQuery = "UPDATE team SET jumlah_anggota = :count WHERE team_id = :team_id";
        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bindParam(':count', $count);
        $updateStmt->bindParam(':team_id', $this->team_id);
        $updateStmt->execute();
    }

    public function hasUserRequested($user_id, $team_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = :user_id AND team_id = :team_id AND status = 'waiting'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':team_id', $team_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    public function isUserMember($user_id, $team_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = :user_id AND team_id = :team_id AND status = 'confirm'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':team_id', $team_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE detail_id = :detail_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':detail_id', $this->detail_id);

        try {
            if ($stmt->execute()) {

                $this->updateTeamMemberCount();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team member delete error: " . $e->getMessage());
            return false;
        }
    }
}
