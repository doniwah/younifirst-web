<?php

namespace Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Config\Database;
use PDO;

abstract class BaseRepositoryTest extends TestCase
{
    protected ?PDO $pdo = null;

    protected function setUp(): void
    {
        try {
            $this->pdo = Database::getConnection('test');
            $this->pdo->beginTransaction();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Database connection failed: ' . $e->getMessage());
        }
    }

    protected function tearDown(): void
    {
        if ($this->pdo && $this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    protected function getPdo(): PDO
    {
        return $this->pdo;
    }
}
