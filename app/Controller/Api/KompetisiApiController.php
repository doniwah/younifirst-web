<?php

namespace App\Controller\Api;

use App\Config\Database;

class KompetisiApiController
{
    private function json($data, $status = 200)
    {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    // ====================================
    // GET: /api/kompetisi
    // ====================================
    public function index()
    {
        $db = Database::getConnection('prod');

        $competitions = $db->query("
            SELECT lomba_id, nama_lomba, tanggal_lomba, kategori, lokasi, deskripsi, hadiah, status
            FROM lomba
            ORDER BY tanggal_lomba DESC
        ")->fetchAll();

        $teams = $db->query("
            SELECT team_id, nama_team, nama_kegiatan, deskripsi_anggota, max_anggota, role_required, status
            FROM team
            ORDER BY team_id DESC
        ")->fetchAll();

        return $this->json([
            "success" => true,
            "competitions" => $competitions,
            "teams" => $teams
        ]);
    }

    // ====================================
    // POST: /api/kompetisi/create-lomba
    // ====================================
    public function createLomba()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(["success" => false, "message" => "Method not allowed"], 405);
        }

        $db = Database::getConnection('prod');

        $id = rand(100000, 999999);
        $tanggal = $_POST['tanggal_lomba'] ?? $_POST['deadline'] ?? null;

        if (!$tanggal) {
            return $this->json(["success" => false, "message" => "Tanggal lomba harus diisi"], 400);
        }

        try {
            $stmt = $db->prepare("
                INSERT INTO lomba (lomba_id, nama_lomba, deskripsi, status, tanggal_lomba, hadiah, kategori, user_id, lokasi)
                VALUES (:id, :nama, :desk, 'waiting', :tanggal, :hadiah, :kategori, :user_id, :lokasi)
            ");

            $stmt->execute([
                ':id' => $id,
                ':nama' => $_POST['nama_lomba'],
                ':desk' => $_POST['deskripsi'] ?? '',
                ':tanggal' => $tanggal,
                ':hadiah' => $_POST['hadiah'] ?? 0,
                ':kategori' => $_POST['kategori'] ?? '',
                ':user_id' => $_POST['user_id'] ?? 1,
                ':lokasi' => $_POST['lokasi'] ?? ''
            ]);

            return $this->json(["success" => true, "message" => "Lomba berhasil dibuat"]);
        } catch (\PDOException $e) {
            return $this->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    public function createTeam()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(["success" => false, "message" => "Method not allowed"], 405);
        }

        $db = Database::getConnection('prod');
        $team_id = rand(100000, 999999);

        try {
            // Insert team
            $stmt = $db->prepare("
                INSERT INTO team (team_id, nama_kegiatan, nama_team, deskripsi_anggota, role_required, max_anggota, role, status)
                VALUES (:id, :kegiatan, :nama, :desk, :role_req, :max, 'ketua', 'waiting')
            ");

            $stmt->execute([
                ':id' => $team_id,
                ':kegiatan' => $_POST['nama_kegiatan'],
                ':nama' => $_POST['nama_team'],
                ':desk' => $_POST['deskripsi'] ?? '',
                ':role_req' => $_POST['role_dibutuhkan'] ?? '-',
                ':max' => $_POST['max_anggota'] ?? 1
            ]);

            // Insert ketua team
            $stmt2 = $db->prepare("
                INSERT INTO detail_anggota (team_id, user_id, tanggal_gabung, role, status)
                VALUES (:team_id, :user_id, NOW(), 'ketua', 'confirm')
            ");

            $stmt2->execute([
                ':team_id' => $team_id,
                ':user_id' => $_POST['user_id'] ?? 1
            ]);

            return $this->json(["success" => true, "message" => "Tim berhasil dibuat"]);
        } catch (\PDOException $e) {
            return $this->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }
}