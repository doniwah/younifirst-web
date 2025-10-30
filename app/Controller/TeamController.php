<?php

namespace App\Controller;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Competition;
use App\App\View;

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

            $team->lomba_id = $_POST['lomba_id'] ?? '';
            $team->nama_team = $_POST['nama_team'] ?? '';
            $team->deskripsi_anggota = $_POST['deskripsi_anggota'] ?? '';

            // Debug log
            error_log("POST Data - lomba_id: " . $team->lomba_id . ", nama_team: " . $team->nama_team);
            error_log("Full POST: " . print_r($_POST, true));

            // Process roles array into comma-separated string
            $roles = isset($_POST['roles']) && is_array($_POST['roles']) ? $_POST['roles'] : [];
            $roles = array_filter($roles, function ($role) {
                return !empty(trim($role));
            });
            $team->role_dibutuhkan = implode(',', array_map('trim', $roles));

            $team->jumlah_anggota = 1; // Pembuat tim otomatis menjadi anggota pertama
            $team->maksimal_anggota = (int)($_POST['maksimal_anggota'] ?? 5);

            if (empty($team->nama_team) || empty($team->lomba_id)) {
                error_log("Validation failed - nama_team empty: " . (empty($team->nama_team) ? 'yes' : 'no') . ", lomba_id empty: " . (empty($team->lomba_id) ? 'yes' : 'no'));
                header('Location: /kompetisi?status=error&message=Nama tim dan lomba harus diisi');
                exit();
            }

            // Validate maksimal anggota
            if ($team->maksimal_anggota < 1) {
                header('Location: /kompetisi?status=error&message=Maksimal anggota minimal 1');
                exit();
            }

            // Create team first
            if ($team->create()) {
                try {
                    // Tambahkan pembuat tim sebagai anggota pertama dengan status confirm
                    $conn = \App\Models\Database::getInstance();
                    $role = $_POST['role_pembuat'] ?? 'ketua';

                    $query = "INSERT INTO detail_angota (tanggal_gabung, role, status, team_id, user_id) 
                              VALUES (NOW(), :role, 'confirm', :team_id, :user_id)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':team_id', $team->team_id);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);

                    if ($stmt->execute()) {
                        error_log("Team member added successfully. Team ID: " . $team->team_id . ", User ID: " . $_SESSION['user_id']);
                        header('Location: /kompetisi?status=success&message=Tim berhasil dibuat! Menunggu persetujuan admin');
                        exit();
                    } else {
                        error_log("Failed to add team member. Error: " . print_r($stmt->errorInfo(), true));
                        header('Location: /kompetisi?status=error&message=Tim dibuat tapi gagal menambahkan anggota');
                        exit();
                    }
                } catch (\PDOException $e) {
                    error_log("Error adding team member: " . $e->getMessage());
                    header('Location: /kompetisi?status=error&message=Error: ' . $e->getMessage());
                    exit();
                }
            } else {
                header('Location: /kompetisi?status=error&message=Gagal membuat tim');
                exit();
            }
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
        $team->team_id = $id;

        if ($team->approve()) {
            header('Location: /dashboard?status=success&message=Tim berhasil di-approve');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal approve tim');
            exit();
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
        $team->team_id = $id;

        if ($team->reject()) {
            header('Location: /dashboard?status=success&message=Tim berhasil di-reject');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal reject tim');
            exit();
        }
    }

    // Get all teams with member info
    public function index()
    {
        // $team = new Team();
        // $stmt = $team->readAll();
        // $teams = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // // Get member count for each team
        // foreach ($teams as &$tm) {
        //     $memberModel = new TeamMember();
        //     $memberStmt = $memberModel->readByTeam($tm['team_id'], 'confirm');
        //     $tm['confirmed_members'] = $memberStmt->rowCount();
        // }

        // header('Content-Type: application/json');
        // echo json_encode($teams);

        View::render('component/kompetisi/index', ['title' => 'YouniFirst']);
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
        $team->team_id = $id;

        if ($team->readOne()) {
            // Get members
            $memberModel = new TeamMember();
            $memberStmt = $memberModel->readByTeam($id, 'confirm');
            $members = $memberStmt->fetchAll(\PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode([
                'team_id' => $team->team_id,
                'nama_team' => $team->nama_team,
                'deskripsi_anggota' => $team->deskripsi_anggota,
                'jumlah_anggota' => $team->jumlah_anggota,
                'members' => $members
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

            $teamMember = new TeamMember();

            // Check if user already requested
            if ($teamMember->hasUserRequested($_SESSION['user_id'], $_POST['team_id'])) {
                header('Location: /kompetisi?status=error&message=Anda sudah mengajukan bergabung dengan tim ini');
                exit();
            }

            // Check if user already a member
            if ($teamMember->isUserMember($_SESSION['user_id'], $_POST['team_id'])) {
                header('Location: /kompetisi?status=error&message=Anda sudah menjadi anggota tim ini');
                exit();
            }

            $teamMember->team_id = $_POST['team_id'] ?? '';
            $teamMember->user_id = $_SESSION['user_id'];
            $teamMember->role = $_POST['role_diminta'] ?? 'anggota';

            // Store additional info in session for notification
            if (!isset($_SESSION['join_requests'])) {
                $_SESSION['join_requests'] = [];
            }

            if (empty($teamMember->team_id) || empty($teamMember->role)) {
                header('Location: /kompetisi?status=error&message=Data wajib harus diisi');
                exit();
            }

            if ($teamMember->createRequest()) {
                // Save detail to session
                $_SESSION['join_requests'][$teamMember->detail_id] = [
                    'alasan_bergabung' => $_POST['alasan_bergabung'] ?? '',
                    'keahlian_pengalaman' => $_POST['keahlian_pengalaman'] ?? '',
                    'portfolio_link' => $_POST['portfolio_link'] ?? '',
                    'kontak' => $_POST['kontak'] ?? ''
                ];

                header('Location: /kompetisi?status=success&message=Pengajuan berhasil dikirim! Tunggu konfirmasi dari pembuat tim');
                exit();
            } else {
                header('Location: /kompetisi?status=error&message=Gagal mengirim pengajuan');
                exit();
            }
        }
    }

    // Get pending requests (untuk notifikasi)
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

        $teamMember = new TeamMember();
        $stmt = $teamMember->readPendingByTeamOwner($_SESSION['user_id']);

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    // Get requests for specific team
    public function getTeamRequests($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $team_id = $params['id'] ?? null;

        if (!$team_id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid team ID']);
            return;
        }

        $teamMember = new TeamMember();
        $stmt = $teamMember->readByTeam($team_id, 'waiting');

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
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

        $teamMember = new TeamMember();
        $teamMember->detail_id = $id;

        if ($teamMember->readOne()) {
            // Get additional info from session if available
            $additionalInfo = $_SESSION['join_requests'][$id] ?? [];

            header('Content-Type: application/json');
            echo json_encode([
                'detail_id' => $teamMember->detail_id,
                'team_id' => $teamMember->team_id,
                'user_id' => $teamMember->user_id,
                'role' => $teamMember->role,
                'status' => $teamMember->status,
                'tanggal_gabung' => $teamMember->tanggal_gabung,
                'alasan_bergabung' => $additionalInfo['alasan_bergabung'] ?? 'Tidak ada keterangan',
                'keahlian_pengalaman' => $additionalInfo['keahlian_pengalaman'] ?? 'Tidak ada keterangan',
                'portfolio_link' => $additionalInfo['portfolio_link'] ?? '',
                'kontak' => $additionalInfo['kontak'] ?? 'Tidak ada keterangan'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Request not found']);
        }
    }

    // Approve member request
    public function approveRequest($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /kompetisi?status=error&message=Invalid request ID');
            exit();
        }

        $teamMember = new TeamMember();
        $teamMember->detail_id = $id;
        $teamMember->readOne();

        if ($teamMember->approve()) {
            // Remove from session after approval
            if (isset($_SESSION['join_requests'][$id])) {
                unset($_SESSION['join_requests'][$id]);
            }

            header('Location: /kompetisi?status=success&message=Pengajuan berhasil diterima');
            exit();
        } else {
            header('Location: /kompetisi?status=error&message=Gagal menerima pengajuan');
            exit();
        }
    }

    // Reject member request
    public function rejectRequest($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /kompetisi?status=error&message=Invalid request ID');
            exit();
        }

        $teamMember = new TeamMember();
        $teamMember->detail_id = $id;

        if ($teamMember->reject()) {
            // Remove from session after rejection
            if (isset($_SESSION['join_requests'][$id])) {
                unset($_SESSION['join_requests'][$id]);
            }

            header('Location: /kompetisi?status=success&message=Pengajuan berhasil ditolak');
            exit();
        } else {
            header('Location: /kompetisi?status=error&message=Gagal menolak pengajuan');
            exit();
        }
    }

    // Delete team
    public function delete($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /kompetisi?status=error&message=Invalid team ID');
            exit();
        }

        $team = new Team();
        $team->team_id = $id;

        if ($team->delete()) {
            header('Location: /kompetisi?status=success&message=Tim berhasil dihapus');
            exit();
        } else {
            header('Location: /kompetisi?status=error&message=Gagal menghapus tim');
            exit();
        }
    }
}