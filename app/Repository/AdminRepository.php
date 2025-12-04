<?php

namespace App\Repository;

use App\Config\Database;

class AdminRepository
{
    private $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getActivityStats()
    {
        // Query untuk menghitung statistik
        $diaktifkan = 0;
        $dihapus = 0;
        $suspended = 0;
        $blocked = 0;

        // Contoh query - sesuaikan dengan struktur database Anda
        $query = "SELECT 
                    SUM(CASE WHEN action_type = 'reactivated' THEN 1 ELSE 0 END) as diaktifkan,
                    SUM(CASE WHEN action_type = 'deleted' THEN 1 ELSE 0 END) as dihapus,
                    SUM(CASE WHEN action_type = 'suspended' THEN 1 ELSE 0 END) as suspended,
                    SUM(CASE WHEN action_type = 'blocked' THEN 1 ELSE 0 END) as blocked
                  FROM activity_logs";
        
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return [
            'diaktifkan' => $result['diaktifkan'] ?? 0,
            'dihapus' => $result['dihapus'] ?? 0,
            'suspended' => $result['suspended'] ?? 0,
            'blocked' => $result['blocked'] ?? 0
        ];
    }

    public function getActivityLogs($limit = null)
{
    $query = "SELECT 
                al.*,
                u.nama_lengkap as user_name,
                u.username as user_username,
                u.email as user_email,
                a.nama_lengkap as admin_name,
                a.username as admin_username
              FROM activity_logs al
              LEFT JOIN users u ON al.user_id = u.user_id
              LEFT JOIN users a ON al.admin_id = a.user_id
              ORDER BY al.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }
    
    $statement = $this->connection->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

    // Format data untuk view
    $logs = [];
    foreach ($results as $row) {
        $logs[] = $this->formatLogItem($row);
    }

    return $logs;
}

private function formatLogItem($row)
{
    $typeConfig = [
        'reactivated' => [
            'color' => 'green',
            'icon' => 'bi bi-check-circle',
            'title' => 'Akun diaktifkan kembali'
        ],
        'suspended' => [
            'color' => 'orange',
            'icon' => 'bi bi-exclamation-triangle',
            'title' => 'Akun disuspend selama 7 hari'
        ],
        'blocked' => [
            'color' => 'red',
            'icon' => 'bi bi-x-octagon',
            'title' => 'Akun diblokir'
        ],
        'deleted' => [
            'color' => 'red',
            'icon' => 'bi bi-trash',
            'title' => 'Akun dihapus permanen'
        ]
    ];

    $type = $row['action_type'] ?? 'reactivated';
    $config = $typeConfig[$type] ?? $typeConfig['reactivated'];

    // Get user name with fallback
    $userName = $row['user_name'] ?: ($row['user_username'] ?: $row['user_email']);
    $adminName = $row['admin_name'] ?: ($row['admin_username'] ?: 'Admin');

    return [
        'type' => $type,
        'color' => $config['color'],
        'icon' => $config['icon'],
        'title' => $config['title'],
        'user' => $userName ?: 'Unknown User',
        'admin' => $adminName,
        'reason' => $row['reason'] ?? null,
        'notes' => $row['notes'] ?? null,
        'date' => $row['created_at']
    ];
}

    public function getUserStats()
    {
        $query = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active,
                    COUNT(CASE WHEN status = 'suspended' THEN 1 END) as suspended,
                    COUNT(CASE WHEN status = 'blocked' THEN 1 END) as blocked
                  FROM users";
        
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return [
            'total' => (int)($result['total'] ?? 0),
            'active' => (int)($result['active'] ?? 0),
            'suspended' => (int)($result['suspended'] ?? 0),
            'blocked' => (int)($result['blocked'] ?? 0)
        ];
    }

    public function getAllUsers($search = null, $role = null, $status = null)
    {
        $query = "SELECT 
                    user_id,
                    username,
                    email,
                    nama_lengkap,
                    role,
                    status,
                    avatar,
                    tgl_lahir,
                    last_active
                  FROM users
                  WHERE 1=1";
        
        $params = [];

        // Filter search
        if ($search) {
            $query .= " AND (username ILIKE :search OR email ILIKE :search OR nama_lengkap ILIKE :search)";
            $params['search'] = "%$search%";
        }

        // Filter role
        if ($role) {
            $query .= " AND role = :role";
            $params['role'] = $role;
        }

        // Filter status
        if ($status) {
            $query .= " AND status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY last_active DESC NULLS LAST, user_id DESC";
        
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        // Format data untuk view
        $users = [];
        foreach ($results as $row) {
            $users[] = $this->formatUserItem($row);
        }

        return $users;
    }

    private function formatUserItem($row)
{
    // Jangan generate UI Avatar URL, biarkan frontend yang handle
    $avatar = $row['avatar'] ?? null;
    
    // Format tanggal bergabung
    $joinedDate = 'N/A';
    if (!empty($row['tgl_lahir'])) {
        $joinedDate = date('d M Y', strtotime($row['tgl_lahir']));
    }

    // Format last active
    $lastActive = 'Tidak pernah';
    if (!empty($row['last_active'])) {
        $lastActiveTime = strtotime($row['last_active']);
        $now = time();
        $diff = $now - $lastActiveTime;

        if ($diff < 60) {
            $lastActive = 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            $lastActive = $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            $lastActive = $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            $lastActive = $days . ' hari yang lalu';
        } else {
            $lastActive = date('d M Y', $lastActiveTime);
        }
    }

    return [
        'user_id' => $row['user_id'],
        'username' => $row['username'] ?? 'Unknown',
        'email' => $row['email'] ?? '',
        'nama_lengkap' => $row['nama_lengkap'] ?? '',
        'jurusan' => $row['jurusan'] ?? '',
        'angkatan' => $row['angkatan'] ?? '',
        'role' => $row['role'] ?? 'mahasiswa',
        'status' => ucfirst($row['status'] ?? 'active'),
        'avatar' => $avatar,
        'joined_date' => $joinedDate,
        'last_active' => $lastActive
    ];
}

    private function generateAvatarUrl($initial)
    {
        // Generate avatar placeholder menggunakan UI Avatars
        $background = substr(md5($initial), 0, 6);
        return "https://ui-avatars.com/api/?name={$initial}&background={$background}&color=fff&size=128";
    }

    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['user_id' => $userId]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateUserStatus($userId, $status, $adminId, $reason = null)
    {
        // Update status user
        $query = "UPDATE users SET status = :status WHERE user_id = :user_id";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            'status' => $status,
            'user_id' => $userId
        ]);

        // Log aktivitas
        $actionType = match($status) {
            'active' => 'reactivated',
            'suspended' => 'suspended',
            'blocked' => 'blocked',
            default => 'reactivated'
        };

        $logQuery = "INSERT INTO activity_logs (user_id, admin_id, action_type, reason) 
                     VALUES (:user_id, :admin_id, :action_type, :reason)";
        $logStatement = $this->connection->prepare($logQuery);
        $logStatement->execute([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'action_type' => $actionType,
            'reason' => $reason
        ]);

        return true;
    }

    public function deleteUser($userId, $adminId, $reason = null)
    {
        // Soft delete atau hard delete
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $statement = $this->connection->prepare($query);
        $statement->execute(['user_id' => $userId]);

        // Log aktivitas
        $logQuery = "INSERT INTO activity_logs (user_id, admin_id, action_type, reason) 
                     VALUES (:user_id, :admin_id, :action_type, :reason)";
        $logStatement = $this->connection->prepare($logQuery);
        $logStatement->execute([
            'user_id' => $userId,
            'admin_id' => $adminId,
            'action_type' => 'deleted',
            'reason' => $reason
        ]);

        return true;
    }

    public function createUser($data)
{
    // Generate user_id
    $userId = 'USR' . strtoupper(substr(md5(uniqid()), 0, 10));
    
    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    
    $query = "INSERT INTO users (user_id, username, email, nama_lengkap, password, jurusan, angkatan, role, status) 
              VALUES (:user_id, :username, :email, :nama_lengkap, :password, :jurusan, :angkatan, :role, 'active')";
    
    $statement = $this->connection->prepare($query);
    $statement->execute([
        'user_id' => $userId,
        'username' => $data['username'],
        'email' => $data['email'],
        'nama_lengkap' => $data['nama_lengkap'],
        'password' => $hashedPassword,
        'jurusan' => $data['jurusan'] ?? null,
        'angkatan' => $data['angkatan'] ?? null,
        'role' => $data['role']
    ]);
    
    return $userId;
}

public function updateUser($userId, $data)
{
    $query = "UPDATE users 
              SET username = :username,
                  email = :email,
                  nama_lengkap = :nama_lengkap,
                  jurusan = :jurusan,
                  angkatan = :angkatan,
                  role = :role
              WHERE user_id = :user_id";
    
    $statement = $this->connection->prepare($query);
    $statement->execute([
        'user_id' => $userId,
        'username' => $data['username'],
        'email' => $data['email'],
        'nama_lengkap' => $data['nama_lengkap'],
        'jurusan' => $data['jurusan'] ?? null,
        'angkatan' => $data['angkatan'] ?? null,
        'role' => $data['role']
    ]);
    
    return true;
}

public function getAllUsersForExport()
{
    $query = "SELECT 
                user_id,
                username,
                email,
                nama_lengkap,
                role,
                status,
                jurusan,
                angkatan
              FROM users
              ORDER BY user_id ASC";
    
    $statement = $this->connection->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

    return $results;
}
}