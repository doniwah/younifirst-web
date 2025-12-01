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
        View::render('component/team/create', [
            'title' => 'Tambah Pencarian Tim',
            'user' => $this->session->current()
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
            exit;
        }

        $userId = $this->session->current(); // Returns string user_id
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }
        
        $teamData = [
            'nama_team' => $_POST['nama_team'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'competition_id' => !empty($_POST['competition_id']) ? (int)$_POST['competition_id'] : null,
            'user_id' => $userId,
            'max_members' => (int)($_POST['max_members'] ?? 5),
            'skills_required' => $_POST['skills_required'] ?? '',
            'contact_info' => $_POST['contact_info'] ?? '',
            'status' => 'active',
            'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null
        ];

        $teamId = $this->teamRepository->createTeam($teamData);

        if ($teamId) {
            // Add creator as team leader
            $this->teamRepository->addTeamMember($teamId, $userId, 'leader');
            header('Location: /team?success=Team berhasil dibuat');
        } else {
            header('Location: /team/create?error=Gagal membuat team');
        }
        exit;
    }

    public function edit($id)
    {
        $team = $this->teamRepository->getTeamById($id);
        
        if (!$team) {
            header('Location: /team?error=Team tidak ditemukan');
            exit;
        }

        View::render('component/team/edit', [
            'title' => 'Edit Pencarian Tim',
            'user' => $this->session->current(),
            'team' => $team
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /team');
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