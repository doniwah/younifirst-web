<?php

namespace Tests\Repository;

use App\Repository\CallRequestRepository;
use App\Repository\AdminRepository;

class CallRequestRepositoryTest extends BaseRepositoryTest
{
    private CallRequestRepository $repository;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CallRequestRepository();
        $this->userRepo = new AdminRepository();
        
        // Inject PDO
        $reflection = new \ReflectionClass($this->repository);
        $property = $reflection->getProperty('connection');
        $property->setAccessible(true);
        $property->setValue($this->repository, $this->pdo);

        $reflectionUser = new \ReflectionClass($this->userRepo);
        $propertyUser = $reflectionUser->getProperty('connection');
        $propertyUser->setAccessible(true);
        $propertyUser->setValue($this->userRepo, $this->pdo);
    }

    public function testCreateRequest()
    {
        // Create user first
        $userData = [
            'username' => 'requester',
            'email' => 'requester@example.com',
            'nama_lengkap' => 'Requester User',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $data = [
            'user_id' => $userId,
            'subject' => 'Test Request',
            'description' => 'This is a test request',
            'priority' => 'high'
        ];

        $requestId = $this->repository->createRequest($data);
        $this->assertNotNull($requestId);

        $requests = $this->repository->getAllRequests();
        $this->assertNotEmpty($requests);
        $this->assertEquals($data['subject'], $requests[0]['subject']);
    }

    public function testUpdateStatus()
    {
        // Create user and request
        $userData = [
            'username' => 'requester2',
            'email' => 'requester2@example.com',
            'nama_lengkap' => 'Requester User 2',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $data = [
            'user_id' => $userId,
            'subject' => 'Test Request 2',
            'description' => 'This is a test request 2',
            'priority' => 'medium'
        ];
        $requestId = $this->repository->createRequest($data);

        // Create admin
        $adminData = [
            'username' => 'admin2',
            'email' => 'admin2@example.com',
            'nama_lengkap' => 'Admin User 2',
            'password' => 'password',
            'role' => 'admin'
        ];
        $adminId = $this->userRepo->createUser($adminData);

        $result = $this->repository->updateStatus($requestId, 'completed', $adminId, 'Done');
        $this->assertTrue($result);

        $stats = $this->repository->getStats();
        $this->assertGreaterThan(0, $stats['completed']);
    }
}
