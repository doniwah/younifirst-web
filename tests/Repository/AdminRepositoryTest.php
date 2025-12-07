<?php

namespace Tests\Repository;

use App\Repository\AdminRepository;
use App\Domain\User;

class AdminRepositoryTest extends BaseRepositoryTest
{
    private AdminRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AdminRepository();
        
        // Inject the PDO connection from BaseRepositoryTest into the repository
        // using reflection because the property is private and hardcoded in constructor
        $reflection = new \ReflectionClass($this->repository);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue($this->repository, $this->pdo);
    }

    public function testCreateUser()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'nama_lengkap' => 'Test User',
            'password' => 'password123',
            'jurusan' => 'Informatika',
            'angkatan' => '2023',
            'role' => 'mahasiswa'
        ];

        $userId = $this->repository->createUser($data);

        $this->assertNotNull($userId);
        $this->assertStringStartsWith('USR', $userId);

        $user = $this->repository->getUserById($userId);
        $this->assertEquals($data['username'], $user['username']);
        $this->assertEquals($data['email'], $user['email']);
    }

    public function testUpdateUser()
    {
        // Create a user first
        $data = [
            'username' => 'updateuser',
            'email' => 'update@example.com',
            'nama_lengkap' => 'Update User',
            'password' => 'password123',
            'jurusan' => 'Informatika',
            'angkatan' => '2023',
            'role' => 'mahasiswa'
        ];
        $userId = $this->repository->createUser($data);

        // Update data
        $updateData = [
            'username' => 'updateduser',
            'email' => 'updated@example.com',
            'nama_lengkap' => 'Updated User',
            'jurusan' => 'Sistem Informasi',
            'angkatan' => '2022',
            'role' => 'admin'
        ];

        $result = $this->repository->updateUser($userId, $updateData);
        $this->assertTrue($result);

        $user = $this->repository->getUserById($userId);
        $this->assertEquals($updateData['username'], $user['username']);
        $this->assertEquals($updateData['email'], $user['email']);
        $this->assertEquals($updateData['role'], $user['role']);
    }

    public function testDeleteUser()
    {
        // Create a user first
        $data = [
            'username' => 'deleteuser',
            'email' => 'delete@example.com',
            'nama_lengkap' => 'Delete User',
            'password' => 'password123',
            'role' => 'mahasiswa'
        ];
        $userId = $this->repository->createUser($data);
        
        // Create an admin for logging
        $adminData = [
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'nama_lengkap' => 'Admin User',
            'password' => 'password123',
            'role' => 'admin'
        ];
        $adminId = $this->repository->createUser($adminData);

        $result = $this->repository->deleteUser($userId, $adminId, 'Test delete');
        $this->assertTrue($result);

        $user = $this->repository->getUserById($userId);
        $this->assertFalse($user);
    }

    public function testGetUserStats()
    {
        $stats = $this->repository->getUserStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('active', $stats);
        $this->assertArrayHasKey('suspended', $stats);
        $this->assertArrayHasKey('blocked', $stats);
    }
}
