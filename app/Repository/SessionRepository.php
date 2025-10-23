<?php

namespace App\Repository;

use App\Domain\Session;
use PDO;

class SessionRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $statement = $this->connection->prepare("
            INSERT INTO sessions (id, user_id, created_at) VALUES (?, ?, NOW())
        ");
        $statement->execute([$session->id, $session->userId]);
        return $session;
    }

    public function findById(string $id): ?Session
    {
        $statement = $this->connection->prepare("SELECT * FROM sessions WHERE id = ?");
        $statement->execute([$id]);
        if ($row = $statement->fetch()) {
            $session = new Session();
            $session->id = $row['id'];
            $session->userId = $row['user_id'];
            return $session;
        }
        return null;
    }

    public function deleteById(string $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
        $statement->execute([$id]);
    }
}
