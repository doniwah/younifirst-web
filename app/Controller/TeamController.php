<?php

namespace App\Controller;

use App\Model\Team;
use App\Model\TeamMember;
use App\Model\Competition;
use App\App\View;
use App\Service\SessionService;
use App\Repository\TeamRepository;

class TeamController
{
    private SessionService $session;
    private TeamRepository $teamRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->teamRepository = new TeamRepository();
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
}