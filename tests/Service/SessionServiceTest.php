<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class SessionServiceTest extends TestCase
{
    public function testCreateSessionReturnsSessionObject()
    {
        try {
            $pdo = \App\Config\Database::getConnection('test');
        } catch (Throwable $e) {
            $this->markTestSkipped('No test database: ' . $e->getMessage());
            return;
        }

        $service = new \App\Service\SessionService();
        $session = $service->create('1');

        $this->assertInstanceOf(\App\Domain\Session::class, $session);
        $this->assertEquals('1', $session->userId);
    }
}
