<?php

namespace App\Controller\Api;

use App\Service\SessionService;
use App\Repository\TeamRepository;

class TeamApiController
{
    private SessionService $session;
    private TeamRepository $teamRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->teamRepository = new TeamRepository();
    }

    /**
     * Get all teams with pagination and filters
     */
    public function getAllTeams()
    {
        header('Content-Type: application/json');

        try {
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, min(100, (int)($_GET['limit'] ?? 10)));
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            $competitionId = $_GET['competition_id'] ?? '';

            $result = $this->teamRepository->getTeamsWithFilters([
                'page' => $page,
                'limit' => $limit,
                'search' => $search,
                'status' => $status,
                'competition_id' => $competitionId
            ]);

            echo json_encode([
                'success' => true,
                'data' => $result['teams'] ?? [],
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $result['total_pages'] ?? 1,
                    'total_items' => $result['total_items'] ?? 0,
                    'items_per_page' => $limit
                ]
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get team by ID
     */
    public function getTeam($id)
    {
        header('Content-Type: application/json');

        try {
            $teamId = (int)$id;
            $team = $this->teamRepository->getTeamById($teamId);

            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $teamMembers = $this->teamRepository->getTeamMembers($teamId);
            $team['members'] = $teamMembers;

            echo json_encode([
                'success' => true,
                'data' => $team
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Create new team
     */
    public function createTeam()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Validation
            $requiredFields = ['nama_team', 'deskripsi', 'competition_id', 'user_id'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Missing required fields: ' . implode(', ', $missingFields)
                ], JSON_PRETTY_PRINT);
                return;
            }

            $teamData = [
                'nama_team' => trim($data['nama_team']),
                'deskripsi' => trim($data['deskripsi']),
                'competition_id' => (int)$data['competition_id'],
                'user_id' => (int)$data['user_id'],
                'max_members' => max(1, (int)($data['max_members'] ?? 5)),
                'skills_required' => trim($data['skills_required'] ?? ''),
                'contact_info' => trim($data['contact_info'] ?? ''),
                'status' => in_array($data['status'] ?? 'active', ['active', 'inactive', 'completed']) ? $data['status'] : 'active',
                'deadline' => $data['deadline'] ?? null
            ];

            $teamId = $this->teamRepository->createTeam($teamData);

            if ($teamId) {
                // Add creator as team member
                $this->teamRepository->addTeamMember($teamId, $teamData['user_id'], 'leader');

                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Team created successfully',
                    'data' => [
                        'team_id' => $teamId,
                        'team' => $this->teamRepository->getTeamById($teamId)
                    ]
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create team'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update team
     */
    public function updateTeam($id)
    {
        header('Content-Type: application/json');

        if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $teamId = (int)$id;
            $team = $this->teamRepository->getTeamById($teamId);

            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $updateData = [];
            $allowedFields = [
                'nama_team',
                'deskripsi',
                'max_members',
                'skills_required',
                'contact_info',
                'status',
                'deadline'
            ];

            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    if (in_array($field, ['max_members'])) {
                        $updateData[$field] = (int)$data[$field];
                    } else {
                        $updateData[$field] = trim($data[$field]);
                    }
                }
            }

            if (empty($updateData)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No valid fields to update'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->teamRepository->updateTeam($teamId, $updateData);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Team updated successfully',
                    'data' => $this->teamRepository->getTeamById($teamId)
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update team'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Delete team
     */
    public function deleteTeam($id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $teamId = (int)$id;
            $team = $this->teamRepository->getTeamById($teamId);

            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->teamRepository->deleteTeam($teamId);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Team deleted successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete team'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get team members
     */
    public function getTeamMembers($teamId)
    {
        header('Content-Type: application/json');

        try {
            $teamId = (int)$teamId;
            $team = $this->teamRepository->getTeamById($teamId);

            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $members = $this->teamRepository->getTeamMembers($teamId);

            echo json_encode([
                'success' => true,
                'data' => $members
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Add member to team
     */
    public function addTeamMember($teamId)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $teamId = (int)$teamId;
            $team = $this->teamRepository->getTeamById($teamId);

            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            if (empty($data['user_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'User ID is required'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $userId = (int)$data['user_id'];

            // Check if team is full
            $currentMembers = $this->teamRepository->getTeamMembersCount($teamId);
            if ($currentMembers >= $team['max_members']) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team is already full'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Check if user is already in the team
            $existingMember = $this->teamRepository->getTeamMember($teamId, $userId);
            if ($existingMember) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'User is already a member of this team'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $role = in_array($data['role'] ?? 'member', ['leader', 'co-leader', 'member'])
                ? $data['role']
                : 'member';

            $success = $this->teamRepository->addTeamMember($teamId, $userId, $role);

            if ($success) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Member added to team successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to add member to team'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Remove member from team
     */
    public function removeTeamMember($teamId, $userId)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $teamId = (int)$teamId;
            $userId = (int)$userId;

            $team = $this->teamRepository->getTeamById($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->teamRepository->removeTeamMember($teamId, $userId);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Member removed from team successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to remove member from team'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update team member role
     */
    public function updateTeamMemberRole($teamId, $userId)
    {
        header('Content-Type: application/json');

        if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $teamId = (int)$teamId;
            $userId = (int)$userId;

            $team = $this->teamRepository->getTeamById($teamId);
            if (!$team) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Team not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            if (empty($data['role'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Role is required'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $allowedRoles = ['leader', 'co-leader', 'member'];
            if (!in_array($data['role'], $allowedRoles)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid role. Allowed roles: ' . implode(', ', $allowedRoles)
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->teamRepository->updateTeamMemberRole($teamId, $userId, $data['role']);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Member role updated successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update member role'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Search teams by skills or name
     */
    public function searchTeams()
    {
        header('Content-Type: application/json');

        try {
            $query = $_GET['q'] ?? '';
            $skills = $_GET['skills'] ?? '';
            $competitionId = $_GET['competition_id'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, min(100, (int)($_GET['limit'] ?? 10)));

            if (empty($query) && empty($skills)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Search query or skills parameter is required'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $result = $this->teamRepository->searchTeams([
                'query' => $query,
                'skills' => $skills,
                'competition_id' => $competitionId,
                'page' => $page,
                'limit' => $limit
            ]);

            echo json_encode([
                'success' => true,
                'data' => $result['teams'] ?? [],
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $result['total_pages'] ?? 1,
                    'total_items' => $result['total_items'] ?? 0,
                    'items_per_page' => $limit
                ]
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get user's teams
     */
    public function getUserTeams($userId)
    {
        header('Content-Type: application/json');

        try {
            $userId = (int)$userId;
            $teams = $this->teamRepository->getUserTeams($userId);

            echo json_encode([
                'success' => true,
                'data' => $teams
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get teams by competition
     */
    public function getTeamsByCompetition($competitionId)
    {
        header('Content-Type: application/json');

        try {
            $competitionId = (int)$competitionId;
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, min(100, (int)($_GET['limit'] ?? 10)));

            $result = $this->teamRepository->getTeamsByCompetition($competitionId, $page, $limit);

            echo json_encode([
                'success' => true,
                'data' => $result['teams'] ?? [],
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $result['total_pages'] ?? 1,
                    'total_items' => $result['total_items'] ?? 0,
                    'items_per_page' => $limit
                ]
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
}