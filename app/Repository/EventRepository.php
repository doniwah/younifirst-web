<?php

namespace App\Repository;

use App\Config\Database;

class EventRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    public function getAllEvents()
    {
        $sql = "
            SELECT 
                event_id,
                nama_event,
                deskripsi,
                tanggal_mulai,
                tanggal_selsai,
                lokasi,
                organizer,
                kapasitas,
                peserta_terdaftar,
                status,
                created_at
            FROM event
            ORDER BY tanggal_mulai DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getEventById($id)
    {
        $sql = "SELECT * FROM event WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createEvent($data)
    {
        $sql = "
            INSERT INTO event 
            (nama_event, deskripsi, tanggal_mulai, tanggal_selesai, lokasi, organizer, kapasitas, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nama_event'],
            $data['deskripsi'],
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['lokasi'],
            $data['organizer'],
            $data['kapasitas'],
            $data['status'] ?? 'upcoming'
        ]);
    }

    public function updateEvent($id, $data)
    {
        $sql = "
            UPDATE event 
            SET nama_event = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ?, 
                lokasi = ?, organizer = ?, kapasitas = ?, status = ?
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nama_event'],
            $data['deskripsi'],
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['lokasi'],
            $data['organizer'],
            $data['kapasitas'],
            $data['status'],
            $id
        ]);
    }

    public function deleteEvent($id)
    {
        $sql = "DELETE FROM event WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}