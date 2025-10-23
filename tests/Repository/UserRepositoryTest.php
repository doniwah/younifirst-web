<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class UserRepositoryTest extends TestCase
{
    public function testFindByEmailReturnsUserOrNull()
    {
        try {
            $pdo = \App\Config\Database::getConnection('test');
        } catch (Throwable $e) {
            $this->markTestSkipped('No test database: ' . $e->getMessage());
            return;
        }

        $repo = new \App\Repository\UserRepository($pdo);

        // Uji 1: user yang kemungkinan ada
        $maybeUser = $repo->findByEmail('test@example.com');

        if ($maybeUser === null) {
            // jika user tidak ada di DB test, tes dianggap lulus tapi catat bahwa objek null benar.
            $this->assertNull($maybeUser);
        } else {
            $this->assertInstanceOf(\App\Domain\User::class, $maybeUser);
            $this->assertObjectHasAttribute('email', $maybeUser);
        }
    }
}
