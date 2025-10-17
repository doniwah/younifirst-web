<?php

namespace App\Models;

use PDO;

class Competition
{
    private $conn;
    private $table = 'lomba';

    public $lomba_id;
    public $nama_lomba;
    public $poster_lomba;
    public $status;
    public $tanggal_lomba;
    public $user_id;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  (nama_lomba, poster_lomba, status, tanggal_lomba, user_id) 
                  VALUES 
                  (:nama_lomba, :poster_lomba, :status, :tanggal_lomba, :user_id)";

        $stmt = $this->conn->prepare($query);


        $this->nama_lomba = htmlspecialchars(strip_tags($this->nama_lomba));
        $this->poster_lomba = htmlspecialchars(strip_tags($this->poster_lomba));
        $this->status = 'confirm';
        $this->tanggal_lomba = htmlspecialchars(strip_tags($this->tanggal_lomba));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));


        $stmt->bindParam(':nama_lomba', $this->nama_lomba);
        $stmt->bindParam(':poster_lomba', $this->poster_lomba);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':tanggal_lomba', $this->tanggal_lomba);
        $stmt->bindParam(':user_id', $this->user_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Competition create error: " . $e->getMessage());
            return false;
        }
    }


    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'confirm' ORDER BY tanggal_lomba DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE lomba_id = :lomba_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lomba_id', $this->lomba_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nama_lomba = $row['nama_lomba'];
            $this->poster_lomba = $row['poster_lomba'];
            $this->status = $row['status'];
            $this->tanggal_lomba = $row['tanggal_lomba'];
            $this->user_id = $row['user_id'];
            return true;
        }

        return false;
    }


    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET nama_lomba = :nama_lomba, 
                      poster_lomba = :poster_lomba, 
                      status = :status, 
                      tanggal_lomba = :tanggal_lomba
                  WHERE lomba_id = :lomba_id";

        $stmt = $this->conn->prepare($query);


        $this->nama_lomba = htmlspecialchars(strip_tags($this->nama_lomba));
        $this->poster_lomba = htmlspecialchars(strip_tags($this->poster_lomba));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->tanggal_lomba = htmlspecialchars(strip_tags($this->tanggal_lomba));
        $this->lomba_id = htmlspecialchars(strip_tags($this->lomba_id));

        $stmt->bindParam(':nama_lomba', $this->nama_lomba);
        $stmt->bindParam(':poster_lomba', $this->poster_lomba);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':tanggal_lomba', $this->tanggal_lomba);
        $stmt->bindParam(':lomba_id', $this->lomba_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Competition update error: " . $e->getMessage());
            return false;
        }
    }

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
        } catch (\PDOException $e) {
            error_log("Competition delete error: " . $e->getMessage());
            return false;
        }
    }
}