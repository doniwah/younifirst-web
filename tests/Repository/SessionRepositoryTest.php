<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class SessionRepositoryTest extends TestCase
{
    public function testSaveReturnsSessionInstance()
    {
        try {
            $pdo = \App\Config\Database::getConnection('test');
        } catch (Throwable $e) {
            $this->markTestSkipped('No test database: ' . $e->getMessage());
            return;
        }

        $repo = new \App\Repository\SessionRepository($pdo);
        $session = new \App\Domain\Session();
        $session->id = uniqid('tst_');
        $session->userId = '1';

        $result = $repo->save($session);

        $this->assertInstanceOf(\App\Domain\Session::class, $result);
        $this->assertEquals($session->id, $result->id);
    }
}
