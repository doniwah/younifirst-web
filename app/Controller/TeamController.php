<?php

namespace App\Controller;

use App\Models\Team;
use App\Models\TeamRequest;
use App\Models\Competition;

class TeamController
{
    // Create new team
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                header('Location: /login?message=Silakan login terlebih dahulu');
                exit();
            }

            $team = new Team();

            $team->nama_tim = $_POST['nama_tim'] ?? '';
            $team->lomba_id = $_POST['lomba_id'] ?? '';
            $team->deskripsi = $_POST['deskripsi'] ?? '';
            $team->role_dibutuhkan = $_POST['role_dibutuhkan'] ?? '';
            $team->anggota_saat_ini = $_POST['anggota_saat_ini'] ?? '1';
            $team->maksimal_anggota = $_POST['maksimal_anggota'] ?? '5';
            $team->user_id = $_SESSION['user_id'];

            if (empty($team->nama_tim) || empty($team->lomba_id)) {
                header('Location: /kompetisi?status=error&message=Nama tim dan lomba harus diisi');
                exit();
            }

            if ($team->create()) {
                header('Location: /kompetisi?status=success&message=Tim berhasil dibuat! Menunggu persetujuan admin');
                exit();
            } else {
                header('Location: /kompetisi?status=error&message=Gagal membuat tim');
                exit();
            }
        }
    }

    // Get all competitions for dropdown
    public function getCompetitions()
    {
        $competition = new Competition();
        $stmt = $competition->readAll();
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // Get team detail
    public function detail($params = [])
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid team ID']);
            return;
        }

        $team = new Team();
        $team->tim_id = $id;

        if ($team->readOne()) {
            header('Content-Type: application/json');
            echo json_encode([
                'tim_id' => $team->tim_id,
                'nama_tim' => $team->nama_tim,
                'lomba_id' => $team->lomba_id,
                'deskripsi' => $team->deskripsi,
                'role_dibutuhkan' => $team->role_dibutuhkan,
                'anggota_saat_ini' => $team->anggota_saat_ini,
                'maksimal_anggota' => $team->maksimal_anggota,
                'status' => $team->status,
                'user_id' => $team->user_id
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Team not found']);
        }
    }

    // Submit request to join team
    public function submitRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                header('Location: /login?message=Silakan login terlebih dahulu');
                exit();
            }

            $teamRequest = new TeamRequest();

            // Check if user already requested
            if ($teamRequest->hasUserRequested($_SESSION['user_id'], $_POST['tim_id'])) {
                header('Location: /kompetisi?status=error&message=Anda sudah mengajukan bergabung dengan tim ini');
                exit();
            }

            $teamRequest->tim_id = $_POST['tim_id'] ?? '';
            $teamRequest->user_id = $_SESSION['user_id'];
            $teamRequest->role_diminta = $_POST['role_diminta'] ?? '';
            $teamRequest->alasan_bergabung = $_POST['alasan_bergabung'] ?? '';
            $teamRequest->keahlian_pengalaman = $_POST['keahlian_pengalaman'] ?? '';
            $teamRequest->portfolio_link = $_POST['portfolio_link'] ?? '';
            $teamRequest->kontak = $_POST['kontak'] ?? '';

            if (empty($teamRequest->tim_id) || empty($teamRequest->role_diminta) || empty($teamRequest->kontak)) {
                header('Location: /kompetisi?status=error&message=Data wajib harus diisi');
                exit();
            }

            if ($teamRequest->create()) {
                header('Location: /kompetisi?status=success&message=Pengajuan berhasil dikirim! Tunggu konfirmasi dari pembuat tim');
                exit();
            } else {
                header('Location: /kompetisi?status=error&message=Gagal mengirim pengajuan');
                exit();
            }
        }
    }

    // Get pending requests for team owner (untuk notifikasi)
    public function getPendingRequests()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        $teamRequest = new TeamRequest();
        $stmt = $teamRequest->readPendingByTeamOwner($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // Get requests for specific team
    public function getTeamRequests($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $tim_id = $params['id'] ?? null;

        if (!$tim_id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid team ID']);
            return;
        }

        // Verify team ownership
        $team = new Team();
        $team->tim_id = $tim_id;
        $team->readOne();

        if ($team->user_id != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden']);
            return;
        }

        $teamRequest = new TeamRequest();
        $stmt = $teamRequest->readByTeam($tim_id);

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // Approve team request (team owner only)
    public function approveRequest($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid request ID');
            exit();
        }

        $teamRequest = new TeamRequest();
        $teamRequest->pengajuan_id = $id;
        $teamRequest->readOne();

        // Verify ownership
        $team = new Team();
        $team->tim_id = $teamRequest->tim_id;
        $team->readOne();

        if ($team->user_id != $_SESSION['user_id']) {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        if ($teamRequest->approve()) {
            header('Location: /dashboard?status=success&message=Pengajuan berhasil diterima');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal menerima pengajuan');
            exit();
        }
    }

    // Reject team request (team owner only)
    public function rejectRequest($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid request ID');
            exit();
        }

        $teamRequest = new TeamRequest();
        $teamRequest->pengajuan_id = $id;
        $teamRequest->readOne();

        // Verify ownership
        $team = new Team();
        $team->tim_id = $teamRequest->tim_id;
        $team->readOne();

        if ($team->user_id != $_SESSION['user_id']) {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        if ($teamRequest->reject()) {
            header('Location: /dashboard?status=success&message=Pengajuan berhasil ditolak');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal menolak pengajuan');
            exit();
        }
    }

    // Approve team (Admin only)
    public function approve($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid team ID');
            exit();
        }

        $team = new Team();
        $team->tim_id = $id;

        if ($team->approve()) {
            header('Location: /dashboard?status=success&message=Tim berhasil di-approve');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal approve tim');
            exit();
        }
    }

    // Get request detail
    public function requestDetail($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid request ID']);
            return;
        }

        $teamRequest = new TeamRequest();
        $teamRequest->pengajuan_id = $id;

        if ($teamRequest->readOne()) {
            // Verify ownership
            $team = new Team();
            $team->tim_id = $teamRequest->tim_id;
            $team->readOne();

            if ($team->user_id != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['message' => 'Forbidden']);
                return;
            }

            header('Content-Type: application/json');
            echo json_encode([
                'pengajuan_id' => $teamRequest->pengajuan_id,
                'tim_id' => $teamRequest->tim_id,
                'user_id' => $teamRequest->user_id,
                'role_diminta' => $teamRequest->role_diminta,
                'alasan_bergabung' => $teamRequest->alasan_bergabung,
                'keahlian_pengalaman' => $teamRequest->keahlian_pengalaman,
                'portfolio_link' => $teamRequest->portfolio_link,
                'kontak' => $teamRequest->kontak,
                'status' => $teamRequest->status
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Request not found']);
        }
    }

    // Reject team (Admin only)
    public function reject($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid team ID');
            exit();
        }

        $team = new Team();
        $team->tim_id = $id;

        if ($team->reject()) {
            header('Location: /dashboard?status=success&message=Tim berhasil di-reject');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal reject tim');
            exit();
        }
    }
}
