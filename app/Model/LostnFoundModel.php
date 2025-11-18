<?php

namespace App\Model;

use PDO;

class LostnFoundModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllItems()
    {
        try {
            $query = "SELECT 
                        lf.id_barang,
                        lf.user_id,
                        lf.kategori,
                        lf.tanggal,
                        lf.lokasi,
                        lf.no_hp,
                        lf.email,
                        lf.deskripsi,
                        lf.nama_barang,
                        u.email AS user_email,
                        u.username AS username
                      FROM lost_found lf
                      LEFT JOIN users u ON lf.user_id = u.user_id
                      ORDER BY lf.tanggal DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching lost_found items: " . $e->getMessage());
            return [];
        }
    }

    public function insertItem($data)
    {
        try {
            $query = "INSERT INTO lost_found 
                     (id_barang, user_id, kategori, lokasi, no_hp, email, deskripsi, nama_barang, tanggal) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $data['id_barang'],
                $data['user_id'],
                $data['kategori'],
                $data['lokasi'],
                $data['no_hp'],
                $data['email'],
                $data['deskripsi'],
                $data['nama_barang']
            ]);
        } catch (\PDOException $e) {
            error_log("Error inserting lost_found item: " . $e->getMessage());
            return false;
        }
    }

    public function isIdExists($id_barang)
    {
        $query = "SELECT COUNT(*) FROM lost_found WHERE id_barang = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_barang]);
        return $stmt->fetchColumn() > 0;
    }
}
