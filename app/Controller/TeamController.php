<?php

namespace App\Controller;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Competition;
use App\App\View;
use App\Service\SessionService;

class TeamController
{
    private SessionService $session;

    public function __construct()
    {
        $this->session = new SessionService();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /kompetisi');
            exit;
        }

        $user = $this->session->current();
        if (!$user) {
            header('Location: /users/login?message=Silakan login terlebih dahulu');
            exit;
        }

        $team = new Team();
        $team->lomba_id = $_POST['lomba_id'] ?? '';
        $team->nama_team = $_POST['nama_team'] ?? '';
        $team->deskripsi_anggota = $_POST['deskripsi_anggota'] ?? '';
        $team->maksimal_anggota = (int)($_POST['maksimal_anggota'] ?? 5);
        $team->jumlah_anggota = 1;


        $roles = $_POST['roles'] ?? [];
        if (is_array($roles)) {
            $roles = array_filter($roles, fn($r) => trim($r) !== '');
            $team->role_dibutuhkan = implode(',', $roles);
        }

        if (empty($team->nama_team) || empty($team->lomba_id)) {
            header('Location: /kompetisi?status=error&message=Nama tim dan lomba wajib diisi');
            exit;
        }

        if ($team->maksimal_anggota < 1) {
            header('Location: /kompetisi?status=error&message=Maksimal anggota minimal 1');
            exit;
        }

        if ($team->create()) {
            $conn = \App\Models\Database::getInstance();
            $role = $_POST['role_pembuat'] ?? 'ketua';

            $stmt = $conn->prepare("
                INSERT INTO detail_angota (tanggal_gabung, role, status, team_id, user_id) 
                VALUES (NOW(), :role, 'confirm', :team_id, :user_id)
            ");
            $stmt->execute([
                ':role' => $role,
                ':team_id' => $team->team_id,
                ':user_id' => $user['id']
            ]);

            header('Location: /kompetisi?status=success&message=Tim berhasil dibuat!');
            exit;
        } else {
            header('Location: /kompetisi?status=error&message=Gagal membuat tim');
            exit;
        }
    }


    public function index()
    {
        View::render('component/kompetisi/index', [
            'title' => 'Kompetisi',
            'user' => $this->session->current()
        ]);
    }

    public function approve($params = [])
    {
        $user = $this->session->current();
        if (!$user || $user['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Akses ditolak');
            exit;
        }

        $id = $params['id'] ?? null;
        if (!$id) {
            header('Location: /dashboard?status=error&message=ID tim tidak valid');
            exit;
        }

        $team = new Team();
        $team->team_id = $id;
        $team->approve()
            ? header('Location: /dashboard?status=success&message=Tim di-approve')
            : header('Location: /dashboard?status=error&message=Gagal approve tim');
        exit;
    }

    public function reject($params = [])
    {
        $user = $this->session->current();
        if (!$user || $user['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Akses ditolak');
            exit;
        }

        $id = $params['id'] ?? null;
        if (!$id) {
            header('Location: /dashboard?status=error&message=ID tim tidak valid');
            exit;
        }

        $team = new Team();
        $team->team_id = $id;
        $team->reject()
            ? header('Location: /dashboard?status=success&message=Tim ditolak')
            : header('Location: /dashboard?status=error&message=Gagal menolak tim');
        exit;
    }


    public function detail($params = [])
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID tim tidak valid']);
            return;
        }

        $team = new Team();
        $team->team_id = $id;
        if ($team->readOne()) {
            $memberModel = new TeamMember();
            $stmt = $memberModel->readByTeam($id, 'confirm');
            $members = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode([
                'team' => $team,
                'members' => $members
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Tim tidak ditemukan']);
        }
    }

    public function delete($params = [])
    {
        $user = $this->session->current();
        if (!$user) {
            header('Location: /users/login?message=Silakan login terlebih dahulu');
            exit;
        }

        $id = $params['id'] ?? null;
        if (!$id) {
            header('Location: /kompetisi?status=error&message=ID tim tidak valid');
            exit;
        }

        $team = new Team();
        $team->team_id = $id;

        $team->delete()
            ? header('Location: /kompetisi?status=success&message=Tim berhasil dihapus')
            : header('Location: /kompetisi?status=error&message=Gagal menghapus tim');
        exit;
    }
}