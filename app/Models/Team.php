<?php

namespace App\Models;

use PDO;
use PDOException;

class Team
{
    private $conn;
    private $table = 'team';

    public $team_id;
    public $lomba_id;
    public $nama_team;
    public $deskripsi_anggota;
    public $role_dibutuhkan;
    public $jumlah_anggota;
    public $maksimal_anggota;
    public $status;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function create()
    {
        do {
            $this->team_id = 'TM' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);


            $checkQuery = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE team_id = :team_id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':team_id', $this->team_id);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        } while ($result['count'] > 0);
        $query = "INSERT INTO " . $this->table . " 
              (team_id, nama_team, deskripsi_anggota, jumlah_anggota, maksimal_anggota, status) 
              VALUES 
              (:team_id, :nama_team, :deskripsi_anggota, :jumlah_anggota, :maksimal_anggota, 'waiting')";

        try {
            $stmt = $this->conn->prepare($query);

            $this->nama_team = htmlspecialchars(strip_tags($this->nama_team));
            $this->deskripsi_anggota = htmlspecialchars(strip_tags($this->deskripsi_anggota ?? ''));
            $this->jumlah_anggota = (int)($this->jumlah_anggota ?? 1);
            $this->maksimal_anggota = (int)($this->maksimal_anggota ?? 5);

            $stmt->bindParam(':team_id', $this->team_id);
            $stmt->bindParam(':nama_team', $this->nama_team);
            $stmt->bindParam(':deskripsi_anggota', $this->deskripsi_anggota);
            $stmt->bindParam(':jumlah_anggota', $this->jumlah_anggota, PDO::PARAM_INT);
            $stmt->bindParam(':maksimal_anggota', $this->maksimal_anggota, PDO::PARAM_INT);

            if ($stmt->execute()) {
                error_log("Team created successfully with ID: " . $this->team_id);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team create error: " . $e->getMessage());
            error_log("Team ID attempted: " . $this->team_id);
            return false;
        }
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'confirm' ORDER BY team_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readAllForAdmin()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY 
                  CASE status 
                    WHEN 'waiting' THEN 1 
                    WHEN 'confirm' THEN 2 
                    ELSE 3 
                  END, team_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE team_id = :team_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nama_team = $row['nama_team'];
            $this->deskripsi_anggota = $row['deskripsi_anggota'];
            $this->jumlah_anggota = $row['jumlah_anggota'];
            $this->maksimal_anggota = $row['maksimal_anggota'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    public function approve()
    {
        $query = "UPDATE " . $this->table . " SET status = 'confirm' WHERE team_id = :team_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team approve error: " . $e->getMessage());
            return false;
        }
    }

    public function reject()
    {
        $query = "UPDATE " . $this->table . " SET status = 'rejected' WHERE team_id = :team_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team reject error: " . $e->getMessage());
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET nama_team = :nama_team, 
                      deskripsi_anggota = :deskripsi_anggota, 
                      jumlah_anggota = :jumlah_anggota 
                  WHERE team_id = :team_id";

        $stmt = $this->conn->prepare($query);

        $this->nama_team = htmlspecialchars(strip_tags($this->nama_team));
        $this->deskripsi_anggota = htmlspecialchars(strip_tags($this->deskripsi_anggota));
        $this->jumlah_anggota = htmlspecialchars(strip_tags($this->jumlah_anggota));
        $this->team_id = htmlspecialchars(strip_tags($this->team_id));

        $stmt->bindParam(':nama_team', $this->nama_team);
        $stmt->bindParam(':deskripsi_anggota', $this->deskripsi_anggota);
        $stmt->bindParam(':jumlah_anggota', $this->jumlah_anggota);
        $stmt->bindParam(':team_id', $this->team_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE team_id = :team_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team delete error: " . $e->getMessage());
            return false;
        }
    }

    public function getMembersCount()
    {
        $query = "SELECT COUNT(*) as count FROM detail_angota WHERE team_id = :team_id AND status = 'confirm'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team_id', $this->team_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] ?? 0;
    }
}