<?php

namespace App\Models;

use PDO;
use PDOException;

class Competition
{
    private $conn;
    private $table = 'lomba';

    public $lomba_id;
    public $nama_lomba;
    public $deskripsi;
    public $status;
    public $tanggal_lomba;
    public $hadiah;
    public $kategori;
    public $user_id;
    public $lokasi;
    public $poster_lomba;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
              (nama_lomba, deskripsi, status, tanggal_lomba, hadiah, kategori, user_id, lokasi, poster_lomba) 
              VALUES 
              (:nama_lomba, :deskripsi, :status, :tanggal_lomba, :hadiah, :kategori, :user_id, :lokasi, :poster_lomba)";

        try {
            $stmt = $this->conn->prepare($query);

            // Sanitize input
            $this->nama_lomba = htmlspecialchars(strip_tags($this->nama_lomba));
            $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi ?? ''));
            $this->status = 'waiting';
            $this->tanggal_lomba = htmlspecialchars(strip_tags($this->tanggal_lomba));
            $this->hadiah = htmlspecialchars(strip_tags($this->hadiah ?? '0'));
            $this->kategori = htmlspecialchars(strip_tags($this->kategori ?? ''));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $this->lokasi = htmlspecialchars(strip_tags($this->lokasi ?? ''));
            $this->poster_lomba = htmlspecialchars(strip_tags($this->poster_lomba ?? ''));

            // Debug log
            error_log("Attempting to insert competition: " . $this->nama_lomba);
            error_log("User ID: " . $this->user_id);
            error_log("Tanggal: " . $this->tanggal_lomba);

            // Bind parameters
            $stmt->bindParam(':nama_lomba', $this->nama_lomba);
            $stmt->bindParam(':deskripsi', $this->deskripsi);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':tanggal_lomba', $this->tanggal_lomba);
            $stmt->bindParam(':hadiah', $this->hadiah);
            $stmt->bindParam(':kategori', $this->kategori);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':lokasi', $this->lokasi);
            $stmt->bindParam(':poster_lomba', $this->poster_lomba);

            if ($stmt->execute()) {
                error_log("Competition inserted successfully with ID: " . $this->conn->lastInsertId());
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Competition create error: " . implode(" | ", $errorInfo));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Competition create exception: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            if (isset($e->errorInfo)) {
                error_log("SQLSTATE: " . $e->errorInfo[0]);
            }
            return false;
        }
    }

    // Read only approved competitions (status = 'confirm')
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'confirm' ORDER BY tanggal_lomba DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read all competitions including waiting (untuk admin)
    public function readAllForAdmin()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY 
                  CASE status 
                    WHEN 'waiting' THEN 1 
                    WHEN 'confirm' THEN 2 
                    ELSE 3 
                  END, tanggal_lomba DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read competitions by user
    public function readByUser($user_id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY tanggal_lomba DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Read single competition
    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE lomba_id = :lomba_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lomba_id', $this->lomba_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nama_lomba = $row['nama_lomba'];
            $this->deskripsi = $row['deskripsi'];
            $this->status = $row['status'];
            $this->tanggal_lomba = $row['tanggal_lomba'];
            $this->hadiah = $row['hadiah'];
            $this->kategori = $row['kategori'];
            $this->user_id = $row['user_id'];
            $this->lokasi = $row['lokasi'];
            $this->poster_lomba = $row['poster_lomba'];
            return true;
        }

        return false;
    }

    // Approve competition (Admin only)
    public function approve()
    {
        $query = "UPDATE " . $this->table . " SET status = 'confirm' WHERE lomba_id = :lomba_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lomba_id', $this->lomba_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Competition approve error: " . $e->getMessage());
            return false;
        }
    }

    // Reject competition (Admin only)
    public function reject()
    {
        $query = "UPDATE " . $this->table . " SET status = 'rejected' WHERE lomba_id = :lomba_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lomba_id', $this->lomba_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Competition reject error: " . $e->getMessage());
            return false;
        }
    }

    // Delete competition
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE lomba_id = :lomba_id";
        $stmt = $this->conn->prepare($query);

        $this->lomba_id = htmlspecialchars(strip_tags($this->lomba_id));
        $stmt->bindParam(':lomba_id', $this->lomba_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Competition delete error: " . $e->getMessage());
            return false;
        }
    }
}