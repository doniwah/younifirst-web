<?php

namespace App\Repository;

use App\Config\Database;

class TeamRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection('prod');
    }

    // Get teams with filters and pagination
    public function getTeamsWithFilters($filters = [])
    {
        $page = $filters['page'] ?? 1;
        $limit = $filters['limit'] ?? 10;
        $offset = ($page - 1) * $limit;
        $search = $filters['search'] ?? '';
        $status = $filters['status'] ?? '';
        $competitionId = $filters['competition_id'] ?? '';

        $whereConditions = [];
        $params = [];

        if (!empty($search)) {
            $whereConditions[] = "(t.nama_team LIKE ? OR t.deskripsi LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $whereConditions[] = "t.status = ?";
            $params[] = $status;
        }

        if (!empty($competitionId)) {
            $whereConditions[] = "t.competition_id = ?";
            $params[] = $competitionId;
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM teams t $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = $countStmt->fetch()['total'];

        // Get teams
        $sql = "
            SELECT t.*, COUNT(tm.id) as current_members
            FROM teams t
            LEFT JOIN team_members tm ON t.id = tm.team_id
            $whereClause
            GROUP BY t.id
            ORDER BY t.created_at DESC
            LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $teams = $stmt->fetchAll();

        return [
            'teams' => $teams,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    // Get team by ID
    public function getTeamById($id)
    {
        $sql = "
            SELECT t.*, COUNT(tm.id) as current_members
            FROM teams t
            LEFT JOIN team_members tm ON t.id = tm.team_id
            WHERE t.id = ?
            GROUP BY t.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create new team
    public function createTeam($data)
    {
        $sql = "
            INSERT INTO teams (nama_team, deskripsi, competition_id, user_id, max_members, skills_required, contact_info, status, deadline)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nama_team'],
            $data['deskripsi'],
            $data['competition_id'] ?? null,
            $data['user_id'],
            $data['max_members'] ?? 5,
            $data['skills_required'] ?? '',
            $data['contact_info'] ?? '',
            $data['status'] ?? 'active',
            $data['deadline'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    // Update team
    public function updateTeam($id, $data)
    {
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }

        $params[] = $id;
        $sql = "UPDATE teams SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete team
    public function deleteTeam($id)
    {
        $sql = "DELETE FROM teams WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Get team members
    public function getTeamMembers($teamId)
    {
        $sql = "
            SELECT tm.*, u.nama, u.email, u.nim, u.jurusan
            FROM team_members tm
            LEFT JOIN users u ON tm.user_id = u.user_id
            WHERE tm.team_id = ?
            ORDER BY tm.joined_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll();
    }

    // Get team members count
    public function getTeamMembersCount($teamId)
    {
        $sql = "SELECT COUNT(*) as total FROM team_members WHERE team_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetch()['total'];
    }

    // Get specific team member
    public function getTeamMember($teamId, $userId)
    {
        $sql = "SELECT * FROM team_members WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId, $userId]);
        return $stmt->fetch();
    }

    // Add team member
    public function addTeamMember($teamId, $userId, $role = 'member')
    {
        $sql = "INSERT INTO team_members (team_id, user_id, role) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$teamId, $userId, $role]);
    }

    // Remove team member
    public function removeTeamMember($teamId, $userId)
    {
        $sql = "DELETE FROM team_members WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$teamId, $userId]);
    }

    // Update team member role
    public function updateTeamMemberRole($teamId, $userId, $role)
    {
        $sql = "UPDATE team_members SET role = ? WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$role, $teamId, $userId]);
    }

    // Search teams
    public function searchTeams($filters = [])
    {
        $query = $filters['query'] ?? '';
        $skills = $filters['skills'] ?? '';
        $competitionId = $filters['competition_id'] ?? '';
        $page = $filters['page'] ?? 1;
        $limit = $filters['limit'] ?? 10;
        $offset = ($page - 1) * $limit;

        $whereConditions = ["t.status = 'active'"];
        $params = [];

        if (!empty($query)) {
            $whereConditions[] = "(t.nama_team LIKE ? OR t.deskripsi LIKE ?)";
            $params[] = "%$query%";
            $params[] = "%$query%";
        }

        if (!empty($skills)) {
            $whereConditions[] = "t.skills_required LIKE ?";
            $params[] = "%$skills%";
        }

        if (!empty($competitionId)) {
            $whereConditions[] = "t.competition_id = ?";
            $params[] = $competitionId;
        }

        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM teams t $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = $countStmt->fetch()['total'];

        // Get teams
        $sql = "
            SELECT t.*, COUNT(tm.id) as current_members
            FROM teams t
            LEFT JOIN team_members tm ON t.id = tm.team_id
            $whereClause
            GROUP BY t.id
            ORDER BY t.created_at DESC
            LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $teams = $stmt->fetchAll();

        return [
            'teams' => $teams,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    // Get user's teams
    public function getUserTeams($userId)
    {
        $sql = "
            SELECT t.*, tm.role, COUNT(tm2.id) as current_members
            FROM teams t
            JOIN team_members tm ON t.id = tm.team_id
            LEFT JOIN team_members tm2 ON t.id = tm2.team_id
            WHERE tm.user_id = ?
            GROUP BY t.id, tm.role
            ORDER BY t.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Get teams by competition
    public function getTeamsByCompetition($competitionId, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM teams WHERE competition_id = ?";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute([$competitionId]);
        $totalItems = $countStmt->fetch()['total'];

        // Get teams
        $sql = "
            SELECT t.*, COUNT(tm.id) as current_members
            FROM teams t
            LEFT JOIN team_members tm ON t.id = tm.team_id
            WHERE t.competition_id = ?
            GROUP BY t.id
            ORDER BY t.created_at DESC
            LIMIT ? OFFSET ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$competitionId, $limit, $offset]);
        $teams = $stmt->fetchAll();

        return [
            'teams' => $teams,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    public function getAllTeamsWithDetails($userRole = 'user')
    {
        // Admin can see all teams (active), non-admin only sees confirmed teams
        $statusCondition = ($userRole === 'admin') 
            ? "t.status = 'active'" 
            : "t.status = 'active' AND (t.approval_status = 'confirm' OR t.approval_status IS NULL)";
        
        $sql = "
            SELECT 
                t.*,
                u.username as creator_name,
                u.email as creator_email,
                u.jurusan as creator_jurusan,
                '' as creator_semester,
                l.nama_lomba as competition_name,
                COUNT(tm.id) as current_members,
                COUNT(ta.id) as total_applicants,
                (t.max_members - COUNT(tm.id)) as members_needed,
                CASE 
                    WHEN (t.max_members - COUNT(tm.id)) <= 1 THEN 'urgent'
                    ELSE 'active'
                END as priority_status
            FROM teams t
            LEFT JOIN users u ON t.user_id = u.user_id
            LEFT JOIN lomba l ON t.competition_id = l.lomba_id
            LEFT JOIN team_members tm ON t.id = tm.team_id
            LEFT JOIN team_applications ta ON t.id = ta.team_id AND ta.status = 'pending'
            WHERE $statusCondition
            GROUP BY t.id, u.username, u.email, u.jurusan, l.nama_lomba
            ORDER BY 
                priority_status DESC,
                t.deadline ASC,
                t.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getTeamWithDetails($id)
    {
        $sql = "
            SELECT 
                t.*,
                u.username as creator_name,
                u.email as creator_email,
                u.jurusan as creator_jurusan,
                '' as creator_semester,
                l.nama_lomba as competition_name,
                COUNT(tm.id) as current_members,
                COUNT(ta.id) as total_applicants,
                (t.max_members - COUNT(tm.id)) as members_needed
            FROM teams t
            LEFT JOIN users u ON t.user_id = u.user_id
            LEFT JOIN lomba l ON t.competition_id = l.lomba_id
            LEFT JOIN team_members tm ON t.id = tm.team_id
            LEFT JOIN team_applications ta ON t.id = ta.team_id AND ta.status = 'pending'
            WHERE t.id = ?
            GROUP BY t.id, u.username, u.email, u.jurusan, l.nama_lomba
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countActiveTeams($userRole = 'user')
    {
        $statusCondition = ($userRole === 'admin') 
            ? "status = 'active'" 
            : "status = 'active' AND (approval_status = 'confirm' OR approval_status IS NULL)";
        
        $sql = "SELECT COUNT(*) as total FROM teams WHERE $statusCondition";
        return $this->db->query($sql)->fetch()['total'];
    }

    public function countUrgentTeams($userRole = 'user')
    {
        $statusCondition = ($userRole === 'admin') 
            ? "t.status = 'active'" 
            : "t.status = 'active' AND (t.approval_status = 'confirm' OR t.approval_status IS NULL)";
        
        $sql = "
            SELECT COUNT(*) as total 
            FROM teams t
            LEFT JOIN team_members tm ON t.id = tm.team_id
            WHERE $statusCondition
            GROUP BY t.id
            HAVING (t.max_members - COUNT(tm.id)) <= 1
        ";
        $result = $this->db->query($sql)->fetchAll();
        return count($result);
    }

    public function getTeamApplications($teamId)
    {
        $sql = "
            SELECT 
                ta.*,
                u.nama,
                u.email,
                u.nim,
                u.jurusan
            FROM team_applications ta
            LEFT JOIN users u ON ta.user_id = u.user_id
            WHERE ta.team_id = ?
            ORDER BY ta.applied_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll();
    }

    public function createTeamApplication($teamId, $userId, $message = '')
    {
        $sql = "
            INSERT INTO team_applications (team_id, user_id, message, applied_at, status) 
            VALUES (?, ?, ?, NOW(), 'pending')
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$teamId, $userId, $message]);
    }

    public function updateApplicationStatus($applicationId, $status)
    {
        $sql = "UPDATE team_applications SET status = ?, processed_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $applicationId]);
    }
}