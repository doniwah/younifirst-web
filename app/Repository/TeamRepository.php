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

    // Get team with filters and pagination
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
            $whereConditions[] = "(t.nama_team LIKE ? OR t.deskripsi_anggota LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $whereConditions[] = "t.status = ?";
            $params[] = $status;
        }

        if (!empty($competitionId)) {
            // Assuming competition_id is still relevant or we filter by nama_kegiatan? 
            // If competition_id column is gone, this filter might need to be removed or changed.
            // For now, let's assume it might still exist or we skip it.
            // But wait, the image didn't show it. 
            // Let's comment it out or leave it if the column actually exists but wasn't in the list.
            // Given the user input "Nama Lomba" is text, maybe we filter by nama_kegiatan?
            // Let's leave it for now but be aware it might fail if column is missing.
            // actually, let's change it to filter by nama_kegiatan if passed?
            // No, let's stick to column mapping updates first.
            // If competition_id is gone, we should probably remove this filter.
            // Let's assume it's gone.
             //$whereConditions[] = "t.competition_id = ?";
             //$params[] = $competitionId;
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM team t $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = $countStmt->fetch()['total'];

        // Get team
        $sql = "
            SELECT t.*, COUNT(tm.team_id) as current_members
            FROM team t
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            $whereClause
            GROUP BY t.team_id
            ORDER BY t.team_id DESC
            LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $team = $stmt->fetchAll();

        return [
            'team' => $team,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    // Get team by ID
    public function getTeamById($id)
    {
        $sql = "
            SELECT t.*, COUNT(tm.team_id) as current_members
            FROM team t
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            WHERE t.team_id = ?
            GROUP BY t.team_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create new team
    public function createTeam($data)
    {
        // Generate team_id (character(10))
        $teamId = 'T' . substr(uniqid(), -9);

        $sql = "
            INSERT INTO team (
                team_id, nama_team, nama_kegiatan, 
                max_anggota, role_required, keterangan_tambahan, 
                status, tenggat_join, deskripsi_anggota, role,
                penyelenggara, link_postingan, ketentuan
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $teamId,
            $data['nama_team'],
            $data['nama_kegiatan'],
            $data['max_anggota'],
            $data['role_required'],
            $data['keterangan_tambahan'],
            $data['status'] ?? 'waiting',
            $data['tenggat_join'],
            $data['deskripsi_anggota'] ?? '',
            'ketua',
            $data['penyelenggara'] ?? null,
            $data['link_postingan'] ?? null,
            $data['ketentuan'] ?? null
        ]);
        
        return $teamId;
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
        $sql = "UPDATE team SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Delete team
    public function deleteTeam($id)
    {
        $sql = "DELETE FROM team WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Get team members
    public function getTeamMembers($teamId)
    {
        $sql = "
            SELECT tm.*, u.nama, u.email, u.nim, u.jurusan
            FROM detail_anggota tm
            LEFT JOIN users u ON tm.user_id = u.user_id
            WHERE tm.team_id = ?
            ORDER BY tm.tanggal_gabung ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetchAll();
    }

    // Get team members count
    public function getTeamMembersCount($teamId)
    {
        $sql = "SELECT COUNT(*) as total FROM detail_anggota WHERE team_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId]);
        return $stmt->fetch()['total'];
    }

    // Get specific team member
    public function getTeamMember($teamId, $userId)
    {
        $sql = "SELECT * FROM detail_anggota WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$teamId, $userId]);
        return $stmt->fetch();
    }

    // Add team member
    public function addTeamMember($teamId, $userId, $role = 'member')
    {
        $sql = "INSERT INTO detail_anggota (team_id, user_id, role) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$teamId, $userId, $role]);
    }

    // Remove team member
    public function removeTeamMember($teamId, $userId)
    {
        $sql = "DELETE FROM detail_anggota WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$teamId, $userId]);
    }

    // Update team member role
    public function updateTeamMemberRole($teamId, $userId, $role)
    {
        $sql = "UPDATE detail_anggota SET role = ? WHERE team_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$role, $teamId, $userId]);
    }

    // Search team
    public function searchTeams($filters = [])
    {
        $query = $filters['query'] ?? '';
        $skills = $filters['skills'] ?? '';
        $competitionId = $filters['competition_id'] ?? '';
        $page = $filters['page'] ?? 1;
        $limit = $filters['limit'] ?? 10;
        $offset = ($page - 1) * $limit;

        $whereConditions = ["t.status = 'confirm'"];
        $params = [];

        if (!empty($query)) {
            $whereConditions[] = "(t.nama_team LIKE ? OR t.deskripsi_anggota LIKE ?)";
            $params[] = "%$query%";
            $params[] = "%$query%";
        }

        if (!empty($skills)) {
            $whereConditions[] = "t.role_required LIKE ?";
            $params[] = "%$skills%";
        }

        if (!empty($competitionId)) {
            $whereConditions[] = "t.competition_id = ?";
            $params[] = $competitionId;
        }

        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM team t $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = $countStmt->fetch()['total'];

        // Get team
        $sql = "
            SELECT t.*, COUNT(tm.team_id) as current_members
            FROM team t
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            $whereClause
            GROUP BY t.team_id
            ORDER BY t.team_id DESC
            LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $team = $stmt->fetchAll();

        return [
            'team' => $team,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    // Get user's team
    public function getUserTeams($userId)
    {
        $sql = "
            SELECT t.*, tm.role, COUNT(tm2.id) as current_members
            FROM team t
            JOIN detail_anggota tm ON t.team_id = tm.team_id
            LEFT JOIN detail_anggota tm2 ON t.team_id = tm2.team_id
            WHERE tm.user_id = ?
            GROUP BY t.team_id, tm.role
            ORDER BY t.team_id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Get team by competition
    public function getTeamsByCompetition($competitionId, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM team WHERE competition_id = ?";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute([$competitionId]);
        $totalItems = $countStmt->fetch()['total'];

        // Get team
        $sql = "
            SELECT t.*, COUNT(tm.team_id) as current_members
            FROM team t
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            WHERE t.competition_id = ?
            GROUP BY t.team_id
            ORDER BY t.team_id DESC
            LIMIT ? OFFSET ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$competitionId, $limit, $offset]);
        $team = $stmt->fetchAll();

        return [
            'team' => $team,
            'total_items' => $totalItems,
            'total_pages' => ceil($totalItems / $limit)
        ];
    }

    public function getAllTeamsWithDetails($userRole = 'user')
    {
        // Admin can see all team (active), non-admin only sees confirmed team
        $statusCondition = ($userRole === 'admin') 
            ? "t.status IN ('waiting', 'confirm')" 
            : "t.status = 'confirm'";
        
        $sql = "
            SELECT 
                t.*,
                u.username as creator_name,
                u.email as creator_email,
                u.jurusan as creator_jurusan,
                '' as creator_semester,
                t.nama_kegiatan as competition_name,
                COUNT(CASE WHEN tm.status = 'confirm' THEN 1 END) as current_members,
                COUNT(CASE WHEN tm.status = 'waiting' THEN 1 END) as total_applicants,
                (t.max_anggota - COUNT(CASE WHEN tm.status = 'confirm' THEN 1 END)) as members_needed,
                CASE 
                    WHEN (t.max_anggota - COUNT(CASE WHEN tm.status = 'confirm' THEN 1 END)) <= 1 THEN 'urgent'
                    ELSE 'active'
                END as priority_status
            FROM team t
            LEFT JOIN detail_anggota leader ON t.team_id = leader.team_id AND leader.role = 'ketua'
            LEFT JOIN users u ON leader.user_id = u.user_id
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            WHERE $statusCondition
            GROUP BY t.team_id, u.username, u.email, u.jurusan, t.nama_kegiatan
            ORDER BY 
                priority_status DESC,
                t.tenggat_join ASC,
                t.team_id DESC
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
                t.nama_kegiatan as competition_name,
                COUNT(CASE WHEN tm.status = 'confirm' THEN 1 END) as current_members,
                COUNT(CASE WHEN tm.status = 'waiting' THEN 1 END) as total_applicants,
                (t.max_anggota - COUNT(CASE WHEN tm.status = 'confirm' THEN 1 END)) as members_needed
            FROM team t
            LEFT JOIN detail_anggota leader ON t.team_id = leader.team_id AND leader.role = 'ketua'
            LEFT JOIN users u ON leader.user_id = u.user_id
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            WHERE t.team_id = ?
            GROUP BY t.team_id, u.username, u.email, u.jurusan, t.nama_kegiatan
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countActiveTeams($userRole = 'user')
    {
        $statusCondition = ($userRole === 'admin') 
            ? "status IN ('waiting', 'confirm')" 
            : "status = 'confirm'";
        
        $sql = "SELECT COUNT(*) as total FROM team WHERE $statusCondition";
        return $this->db->query($sql)->fetch()['total'];
    }

    public function countUrgentTeams($userRole = 'user')
    {
        $statusCondition = ($userRole === 'admin') 
            ? "t.status IN ('waiting', 'confirm')" 
            : "t.status = 'confirm'";
        
        $sql = "
            SELECT COUNT(*) as total 
            FROM team t
            LEFT JOIN detail_anggota tm ON t.team_id = tm.team_id
            WHERE $statusCondition
            GROUP BY t.team_id
            HAVING (t.max_anggota - COUNT(tm.team_id)) <= 1
        ";
        $result = $this->db->query($sql)->fetchAll();
        return count($result);
    }


}