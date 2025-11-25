<?php

namespace App\Controller;

use App\Config\Database;
use App\App\View;

class KompetisiController
{
    public function index()
    {
        $db = Database::getConnection('prod');

        // Ambil semua lomba
        $query = $db->query("
            SELECT lomba_id, nama_lomba, tanggal_lomba, kategori, lokasi 
            FROM lomba
            ORDER BY tanggal_lomba DESC
        ");

        $lombas = $query->fetchAll();

        // Kirim ke view
        View::render('kompetisi/index', [
            'title' => 'Kompetisi',
            'lombas' => $lombas
        ]);
    }

    public function createLomba()
    {
        $db = Database::getConnection('prod');

        $id = "LMB" . rand(10000,99999);
        $nama = $_POST['nama_lomba'];
        $tanggal = $_POST['tanggal_lomba'];
        $kategori = $_POST['kategori'];
        $lokasi = $_POST['lokasi'];
        $deskripsi = $_POST['deskripsi'];
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("
            INSERT INTO lomba (lomba_id, nama_lomba, deskripsi, status, tanggal_lomba, hadiah, kategori, user_id, lokasi)
            VALUES (:id, :nama, :desk, 'waiting', :tanggal, 0, :kategori, :user_id, :lokasi)
        ");

        $stmt->execute([
            ':id' => $id,
            ':nama' => $nama,
            ':desk' => $deskripsi,
            ':tanggal' => $tanggal,
            ':kategori' => $kategori,
            ':user_id' => $user_id,
            ':lokasi' => $lokasi
        ]);

        header("Location: /kompetisi?success=lomba_created");
        exit;
    }

    public function createTeam()
    {
        $db = Database::getConnection('prod');

        $team_id = "TIM" . rand(10000,99999);
        $nama_team = $_POST['nama_team'];
        $nama_kegiatan = $_POST['nama_kegiatan'];
        $deskripsi = $_POST['deskripsi'];
        $max_anggota = $_POST['max_anggota'];
        $user_id = $_SESSION['user_id'];

        // Insert team
        $stmt = $db->prepare("
            INSERT INTO team (team_id, nama_kegiatan, nama_team, deskripsi_anggota, role_required, max_anggota, role, status)
            VALUES (:id, :kegiatan, :nama, :desk, '-', :max, 'ketua', 'waiting')
        ");

        $stmt->execute([
            ':id' => $team_id,
            ':kegiatan' => $nama_kegiatan,
            ':nama' => $nama_team,
            ':desk' => $deskripsi,
            ':max' => $max_anggota
        ]);

        // Insert user sebagai ketua
        $stmt2 = $db->prepare("
            INSERT INTO detail_anggota (team_id, user_id, tanggal_gabung, role, status)
            VALUES (:team_id, :user_id, NOW(), 'ketua', 'confirm')
        ");
        $stmt2->execute([
            ':team_id' => $team_id,
            ':user_id' => $user_id
        ]);

        header("Location: /kompetisi?success=team_created");
        exit;
    }
}