<?php

namespace App\Models;

use PDO;
use PDOException;

class Team
{
    private $conn;
    private $table = 'team';

    public $team_id;
    public $nama_team;
    public $deskripsi_anggota;
    public $jumlah_anggota;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
              (nama_team, deskripsi_anggota, jumlah_anggota) 
              VALUES 
              (:nama_team, :deskripsi_anggota, :jumlah_anggota)";

        try {
            $stmt = $this->conn->prepare($query);

            $this->nama_team = htmlspecialchars(strip_tags($this->nama_team));
            $this->deskripsi_anggota = htmlspecialchars(strip_tags($this->deskripsi_anggota ?? ''));
            $this->jumlah_anggota = htmlspecialchars(strip_tags($this->jumlah_anggota ?? '1'));

            $stmt->bindParam(':nama_team', $this->nama_team);
            $stmt->bindParam(':deskripsi_anggota', $this->deskripsi_anggota);
            $stmt->bindParam(':jumlah_anggota', $this->jumlah_anggota);

            if ($stmt->execute()) {
                $this->team_id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Team create error: " . $e->getMessage());
            return false;
        }
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY team_id DESC";
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
            return true;
        }
        return false;
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

    // Get team members count
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
