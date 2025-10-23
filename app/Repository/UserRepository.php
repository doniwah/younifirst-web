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
            $user = new User();
            $user->user_id = $row['user_id'];
            $user->email = $row['email'];
            $user->username = $row['username'];
            $user->jurusan = $row['jurusan'];
            $user->role = $row['role'];
            $user->password = $row['password'];
            return $user;
        }
        return null;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE user_id = ?");
        $statement->execute([$id]);

        if ($row = $statement->fetch()) {
            $user = new User();
            $user->user_id = $row['user_id'];
            $user->email = $row['email'];
            $user->username = $row['username'];
            $user->jurusan = $row['jurusan'];
            $user->role = $row['role'];
            $user->password = $row['password'];
            return $user;
        }
        return null;
    }
}
