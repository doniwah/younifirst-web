<?php

namespace App\Repository;

use App\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($row = $stmt->fetch()) {
            $user = new User();
            $user->id = $row['user_id'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            return $user;
        }
        return null;
    }
}