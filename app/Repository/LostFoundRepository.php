<?php

namespace App\Repository;

use App\Config\Database;

class LostFoundRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    /**
     * Get all lost/found items
     */
    public function getAllItems()
    {
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            ORDER BY lf.tanggal DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get item by ID
     */
    public function getItemById($id)
    {
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            WHERE lf.id_barang = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get item by id_barang (legacy support)
     */
    public function getItemByIdBarang($idBarang)
    {
        $sql = "SELECT * FROM lost_found WHERE id_barang = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idBarang]);
        return $stmt->fetch();
    }

    /**
     * Create new item
     */
    public function createItem($data)
    {
        $sql = "
            INSERT INTO lost_found 
            (id_barang, user_id, kategori, nama_barang, deskripsi, lokasi, tanggal, no_hp, email, foto_barang, status) 
            VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['id_barang'],
            $data['user_id'],
            $data['kategori'],
            $data['nama_barang'],
            $data['deskripsi'],
            $data['lokasi'],
            $data['no_hp'],
            $data['email'] ?? null,
            $data['foto_barang'] ?? null,
            $data['status'] ?? 'aktif'
        ]);
        
        return $result ? $data['id_barang'] : false;
    }

    /**
     * Update item
     */
    public function updateItem($id, $data)
    {
        $fields = [];
        $params = [];
        
        $allowedFields = [
            'kategori', 'nama_barang', 'deskripsi', 'lokasi', 
            'no_hp', 'email', 'foto_barang', 'status'
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
        $sql = "UPDATE lost_found SET " . implode(', ', $fields) . " WHERE id_barang = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete item
     */
    public function deleteItem($id)
    {
        $sql = "DELETE FROM lost_found WHERE id_barang = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Update status (mark as complete)
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE lost_found SET status = ? WHERE id_barang = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    /**
     * Get items by category
     */
    public function getItemsByCategory($kategori)
    {
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            WHERE lf.kategori = ?
            ORDER BY lf.tanggal DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$kategori]);
        return $stmt->fetchAll();
    }

    /**
     * Get items by status
     */
    public function getItemsByStatus($status)
    {
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            WHERE lf.status = ?
            ORDER BY lf.tanggal DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    /**
     * Get user's items
     */
    public function getUserItems($userId)
    {
        $sql = "
            SELECT * FROM lost_found 
            WHERE user_id = ?
            ORDER BY tanggal DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Check if id_barang exists
     */
    public function isIdExists($idBarang)
    {
        $sql = "SELECT COUNT(*) FROM lost_found WHERE id_barang = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idBarang]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Search items
     */
    public function searchItems($query)
    {
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            WHERE lf.nama_barang ILIKE ? 
               OR lf.deskripsi ILIKE ? 
               OR lf.lokasi ILIKE ?
            ORDER BY lf.tanggal DESC
        ";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    /**
     * Get items with filters
     */
    public function getItemsWithFilters($filters = [])
    {
        $whereConditions = [];
        $params = [];
        
        if (!empty($filters['kategori'])) {
            $whereConditions[] = "lf.kategori = ?";
            $params[] = $filters['kategori'];
        }
        
        if (!empty($filters['status'])) {
            $whereConditions[] = "lf.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereConditions[] = "(lf.nama_barang ILIKE ? OR lf.deskripsi ILIKE ? OR lf.lokasi ILIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "
            SELECT 
                lf.*,
                u.username,
                u.email as user_email
            FROM lost_found lf
            LEFT JOIN users u ON lf.user_id = u.user_id
            $whereClause
            ORDER BY lf.tanggal DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count items by category
     */
    public function countByCategory($kategori)
    {
        $sql = "SELECT COUNT(*) FROM lost_found WHERE kategori = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$kategori]);
        return $stmt->fetchColumn();
    }

    /**
     * Count items by status
     */
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) FROM lost_found WHERE status = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }
}
