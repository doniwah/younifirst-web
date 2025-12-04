<?php

namespace App\Repository;

use App\Domain\User;
use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);

        if ($row = $statement->fetch()) {
            return $this->mapToUser($row);
        }
        return null;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE user_id = ?");
        $statement->execute([$id]);

        if ($row = $statement->fetch()) {
            return $this->mapToUser($row);
        }
        return null;
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET 
            nama_lengkap = ?, 
            angkatan = ?, 
            tgl_lahir = ?, 
            is_notification_active = ? 
            WHERE user_id = ?");
        
        $statement->execute([
            $user->nama_lengkap,
            $user->angkatan,
            $user->tgl_lahir,
            $user->is_notification_active ? 1 : 0,
            $user->user_id
        ]);

        return $user;
    }

    private function mapToUser(array $row): User
    {
        $user = new User();
        $user->user_id = $row['user_id'];
        $user->email = $row['email'];
        $user->username = $row['username'];
        $user->jurusan = $row['jurusan'];
        $user->role = $row['role'];
        $user->password = $row['password'];
        $user->nama_lengkap = $row['nama_lengkap'] ?? null;
        $user->angkatan = $row['angkatan'] ?? null;
        $user->tgl_lahir = $row['tgl_lahir'] ?? null;
        $user->is_notification_active = (bool)($row['is_notification_active'] ?? true);
        return $user;
    }
}
