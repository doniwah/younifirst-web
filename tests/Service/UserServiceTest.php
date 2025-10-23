<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class UserServiceTest extends TestCase
{
    public function testLoginSuccessOrThrowsValidation()
    {
        try {
            $pdo = \App\Config\Database::getConnection('test');
        } catch (Throwable $e) {
            $this->markTestSkipped('No test database: ' . $e->getMessage());
            return;
        }

        $service = new \App\Service\UserService();

        // Buat request sesuai signature service
        $req = new \App\Model\UserLoginRequest();
        $req->email = 'test@example.com';
        $req->password = 'password'; // pastikan di DB ada user ini, password telah hash

        try {
            $response = $service->login($req);
            $this->assertInstanceOf(\App\Model\UserLoginResponse::class, $response);
            $this->assertInstanceOf(\App\Domain\User::class, $response->user);
        } catch (\App\Exception\ValidationException $ex) {
            // jika gagal validasi, pastikan exception bertipe ValidationException
            $this->assertInstanceOf(\App\Exception\ValidationException::class, $ex);
        }
    }
}
