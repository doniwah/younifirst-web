<?php

namespace App\Controller;

use App\Model\Team;
use App\Model\TeamMember;
use App\Model\Competition;
use App\App\View;
use App\Service\SessionService;
use App\Repository\TeamRepository;
use App\Repository\DetailAnggotaRepository;

class TeamController
{
    private SessionService $session;
    private TeamRepository $teamRepository;
    private DetailAnggotaRepository $detailAnggotaRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->teamRepository = new TeamRepository();
        $this->detailAnggotaRepository = new DetailAnggotaRepository();
    }

    public function index()
    {
        $userId = $this->session->current();
        
        // Get user role from database
        $db = \App\Config\Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        // Admin can see all teams, non-admin only sees confirmed teams
        $teams = $this->teamRepository->getAllTeamsWithDetails($userRole);

        View::render('component/team/index', [
            'title' => 'Team Search Management',
            'user' => $userId,
            'userRole' => $userRole,
            'teams' => $teams,
            'total_teams' => count($teams),
            'active_teams' => $this->teamRepository->countActiveTeams($userRole),
            'urgent_teams' => $this->teamRepository->countUrgentTeams($userRole)
        ]);
    }

    public function create()
    {
        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        View::render('component/team/create', [
            'title' => 'Buat Tim Baru',
            'user' => $userId
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team/create');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login?error=' . urlencode('Silakan login terlebih dahulu'));
            exit;
        }

        try {
            // Validate required fields
            if (empty($_POST['nama_team'])) {
                header('Location: /team/create?error=' . urlencode('Nama team harus diisi'));
                exit;
            }

            if (empty($_POST['max_members'])) {
                header('Location: /team/create?error=' . urlencode('Jumlah member maksimal harus diisi'));
                exit;
            }

            if (empty($_POST['deadline'])) {
                header('Location: /team/create?error=' . urlencode('Batas tanggal pendaftaran harus diisi'));
                exit;
            }

            if (empty($_POST['nama_lomba'])) {
                header('Location: /team/create?error=' . urlencode('Nama lomba harus diisi'));
                exit;
            }

            // Collect positions data
            $positions = [];
            if (!empty($_POST['position_name']) && is_array($_POST['position_name'])) {
                foreach ($_POST['position_name'] as $index => $name) {
                    if (!empty($name)) {
                        $positions[] = [
                            'nama' => trim($name),
                            'jumlah' => (int)($_POST['position_qty'][$index] ?? 1),
                            'requirements' => trim($_POST['position_req'][$index] ?? '')
                        ];
                    }
                }
            }

            if (empty($positions)) {
                header('Location: /team/create?error=' . urlencode('Minimal satu posisi harus diisi'));
                exit;
            }

            // Collect extra info
            $extraInfo = [
                'penyelenggara' => trim($_POST['penyelenggara'] ?? ''),
                'link_postingan' => trim($_POST['link_postingan'] ?? '')
            ];

            // Prepare team data
            $teamData = [
                'nama_team' => trim($_POST['nama_team']),
                'nama_kegiatan' => trim($_POST['nama_lomba']),
                'max_anggota' => (int)$_POST['max_members'],
                'role_required' => $positionsString,
                'keterangan_tambahan' => json_encode($extraInfo),
                'status' => 'waiting',
                'tenggat_join' => $_POST['deadline'],
                'deskripsi_anggota' => ''
            ];

            // Create team
            $teamId = $this->teamRepository->createTeam($teamData);

            if ($teamId) {
                // Add creator as team leader
                $addLeaderSuccess = $this->detailAnggotaRepository->addAnggotaConfirmed($teamId, $userId, 'ketua');
                
                if ($addLeaderSuccess) {
                    header('Location: /kompetisi?success=' . urlencode('Tim berhasil dibuat! Menunggu konfirmasi dari admin.'));
                } else {
                    // Team created but failed to add leader
                    header('Location: /kompetisi?error=' . urlencode('Tim dibuat tetapi gagal menambahkan ketua. Silakan hubungi admin.'));
                }
            } else {
                header('Location: /team/create?error=' . urlencode('Gagal membuat tim. Silakan coba lagi.'));
            }
        } catch (\PDOException $e) {
            error_log("Database error creating team: " . $e->getMessage());
            header('Location: /team/create?error=' . urlencode('Gagal membuat tim: ' . $e->getMessage()));
        } catch (\Exception $e) {
            error_log("Error creating team: " . $e->getMessage());
            header('Location: /team/create?error=' . urlencode('Terjadi kesalahan: ' . $e->getMessage()));
        }
        exit;
    }

    public function edit($id)
    {
        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        $team = $this->teamRepository->getTeamById($id);
        
        if (!$team) {
            header('Location: /team?error=Team tidak ditemukan');
            exit;
        }

        View::render('component/team/edit', [
            'title' => 'Edit Pencarian Tim',
            'user' => $userId,
            'team' => $team
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        $updateData = [
            'nama_team' => $_POST['nama_team'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'max_members' => (int)($_POST['max_members'] ?? 5),
            'skills_required' => $_POST['skills_required'] ?? '',
            'contact_info' => $_POST['contact_info'] ?? '',
            'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null
        ];

        $success = $this->teamRepository->updateTeam($id, $updateData);

        if ($success) {
            header('Location: /team?success=Team berhasil diupdate');
        } else {
            header('Location: /team/edit/' . $id . '?error=Gagal mengupdate team');
        }
        exit;
    }

    public function delete($id)
    {
        $success = $this->teamRepository->deleteTeam($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Team berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus team']);
        }
    }

    /**
     * Join team - user bergabung ke team dengan status waiting
     */
    public function joinTeam($teamId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        // Check if team exists
        $team = $this->teamRepository->getTeamById($teamId);
        if (!$team) {
            echo json_encode(['success' => false, 'message' => 'Team tidak ditemukan']);
            exit;
        }

        // Check if user already in team
        if ($this->detailAnggotaRepository->isUserInTeam($teamId, $userId)) {
            echo json_encode(['success' => false, 'message' => 'Anda sudah terdaftar di team ini']);
            exit;
        }

        // Check if team is full
        $currentMembers = $this->detailAnggotaRepository->countConfirmedAnggota($teamId);
        if ($currentMembers >= $team['max_members']) {
            echo json_encode(['success' => false, 'message' => 'Team sudah penuh']);
            exit;
        }

        // Add user to team with waiting status
        $success = $this->detailAnggotaRepository->addAnggota($teamId, $userId, 'anggota');

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Permintaan bergabung berhasil dikirim, menunggu konfirmasi ketua team']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengirim permintaan bergabung']);
        }
    }

    /**
     * Confirm member - ketua team konfirmasi anggota
     */
    public function confirmMember($teamId, $userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $currentUserId = $this->session->current();
        
        if (!$currentUserId) {
            header('Location: /users/login');
            exit;
        }

        // Check if current user is team leader
        if (!$this->detailAnggotaRepository->isTeamLeader($teamId, $currentUserId)) {
            echo json_encode(['success' => false, 'message' => 'Hanya ketua team yang dapat mengkonfirmasi anggota']);
            exit;
        }

        // Check if team is full
        $team = $this->teamRepository->getTeamById($teamId);
        $currentMembers = $this->detailAnggotaRepository->countConfirmedAnggota($teamId);
        
        if ($currentMembers >= $team['max_members']) {
            echo json_encode(['success' => false, 'message' => 'Team sudah penuh']);
            exit;
        }

        // Confirm member
        $success = $this->detailAnggotaRepository->updateStatusAnggota($teamId, $userId, 'confirm');

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Anggota berhasil dikonfirmasi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengkonfirmasi anggota']);
        }
    }

    /**
     * Reject member - ketua team reject anggota
     */
    public function rejectMember($teamId, $userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $currentUserId = $this->session->current();
        
        if (!$currentUserId) {
            header('Location: /users/login');
            exit;
        }

        // Check if current user is team leader
        if (!$this->detailAnggotaRepository->isTeamLeader($teamId, $currentUserId)) {
            echo json_encode(['success' => false, 'message' => 'Hanya ketua team yang dapat menolak anggota']);
            exit;
        }

        // Remove member (or update status to rejected)
        $success = $this->detailAnggotaRepository->removeAnggota($teamId, $userId);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Anggota berhasil ditolak']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menolak anggota']);
        }
    }

    /**
     * Get team members - view all members and pending requests
     */
    public function getTeamMembers($teamId)
    {
        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        // Get team details
        $team = $this->teamRepository->getTeamWithDetails($teamId);
        
        if (!$team) {
            header('Location: /team?error=Team tidak ditemukan');
            exit;
        }

        // Check if user is team leader
        $isLeader = $this->detailAnggotaRepository->isTeamLeader($teamId, $userId);

        // Get confirmed members
        $confirmedMembers = $this->detailAnggotaRepository->getConfirmedAnggota($teamId);

        // Get pending requests (only for team leader)
        $pendingRequests = [];
        if ($isLeader) {
            $pendingRequests = $this->detailAnggotaRepository->getPendingRequests($teamId);
        }

        View::render('component/team/members', [
            'title' => 'Anggota Team - ' . $team['nama_team'],
            'user' => $userId,
            'team' => $team,
            'isLeader' => $isLeader,
            'confirmedMembers' => $confirmedMembers,
            'pendingRequests' => $pendingRequests
        ]);
    }

    /**
     * Leave team - user keluar dari team
     */
    public function leaveTeam($teamId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        // Check if user is team leader (leader cannot leave)
        if ($this->detailAnggotaRepository->isTeamLeader($teamId, $userId)) {
            echo json_encode(['success' => false, 'message' => 'Ketua team tidak dapat keluar dari team. Hapus team jika ingin keluar.']);
            exit;
        }

        // Remove user from team
        $success = $this->detailAnggotaRepository->removeAnggota($teamId, $userId);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Berhasil keluar dari team']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal keluar dari team']);
        }
    }
}