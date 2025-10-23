<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class DatabaseTest extends TestCase
{
    public function testGetConnectionReturnsPdo()
    {
        try {
            $pdo = \App\Config\Database::getConnection('test');
        } catch (Throwable $e) {
            $this->markTestSkipped('Database connection not available: ' . $e->getMessage());
            return;
        }

        $this->assertInstanceOf(PDO::class, $pdo);
    }
}
