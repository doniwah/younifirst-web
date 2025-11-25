<?php

namespace App\Repository;

use App\Config\Database;

class DashboardRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    public function getStatKompetisi()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM lomba WHERE status = 'confirm'")
            ->fetch()['total'];
    }

    public function getStatLost()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM lost_found")
            ->fetch()['total'];
    }

    public function getStatEvent()
    {
        return $this->db->query("SELECT COUNT(*) AS total FROM event")
            ->fetch()['total'];
    }

    public function getLatestKompetisi()
    {
        $sql = "
            SELECT nama_lomba, kategori, tanggal_lomba
            FROM lomba
            WHERE status = 'confirm'
            ORDER BY tanggal_lomba ASC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getLatestLost()
    {
        $sql = "
            SELECT nama_barang, lokasi, tanggal, kategori
            FROM lost_found
            ORDER BY tanggal DESC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getLatestEvent()
    {
        $sql = "
            SELECT nama_event, tanggal_mulai, lokasi
            FROM event
            WHERE tanggal_mulai >= CURRENT_DATE
            ORDER BY tanggal_mulai ASC
            LIMIT 2
        ";
        return $this->db->query($sql)->fetchAll();
    }
}
