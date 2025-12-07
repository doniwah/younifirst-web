<?php

namespace Tests\Repository;

use App\Repository\DetailAnggotaRepository;
use App\Repository\TeamRepository;
use App\Repository\AdminRepository;

class DetailAnggotaRepositoryTest extends BaseRepositoryTest
{
    private DetailAnggotaRepository $repository;
    private TeamRepository $teamRepo;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DetailAnggotaRepository();
        $this->teamRepo = new TeamRepository();
        $this->userRepo = new AdminRepository();
        
        // Inject PDO
        $reflection = new \ReflectionClass($this->repository);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->repository, $this->pdo);

        $reflectionTeam = new \ReflectionClass($this->teamRepo);
        $propertyTeam = $reflectionTeam->getProperty('db');
        $propertyTeam->setAccessible(true);
        $propertyTeam->setValue($this->teamRepo, $this->pdo);

        $reflectionUser = new \ReflectionClass($this->userRepo);
        $propertyUser = $reflectionUser->getProperty('connection');
        $propertyUser->setAccessible(true);
        $propertyUser->setValue($this->userRepo, $this->pdo);
    }

    public function testAddAnggota()
    {
        // Create user
        $userData = [
            'username' => 'member1',
            'email' => 'member1@example.com',
            'nama_lengkap' => 'Member User 1',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        // Create team
        $teamData = [
            'nama_team' => 'Test Team',
            'deskripsi' => 'Test Description',
            'lomba_id' => 1, // Assuming lomba_id 1 exists or not checked
            'leader_id' => $userId, // Assuming leader is also a member
            'max_members' => 5
        ];
        // We need to insert team manually or use TeamRepo if it supports returning ID
        // TeamRepo::createTeam returns ID? Let's check TeamRepo later.
        // For now, let's insert manually to be safe and fast
        $stmt = $this->pdo->prepare("INSERT INTO teams (nama_team, deskripsi, lomba_id, leader_id, max_members) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$teamData['nama_team'], $teamData['deskripsi'], $teamData['lomba_id'], $teamData['leader_id'], $teamData['max_members']]);
        $teamId = $this->pdo->lastInsertId();

        // Add user as member
        $result = $this->repository->addAnggota($teamId, $userId);
        $this->assertTrue($result);

        $members = $this->repository->getAnggotaByTeam($teamId);
        $this->assertCount(1, $members);
        $this->assertEquals($userId, $members[0]['user_id']);
    }

    public function testUpdateStatusAnggota()
    {
        // Setup user and team
        $userData = [
            'username' => 'member2',
            'email' => 'member2@example.com',
            'nama_lengkap' => 'Member User 2',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $stmt = $this->pdo->prepare("INSERT INTO teams (nama_team, deskripsi, lomba_id, leader_id, max_members) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Test Team 2', 'Desc', 1, $userId, 5]);
        $teamId = $this->pdo->lastInsertId();

        $this->repository->addAnggota($teamId, $userId);

        // Update status
        $result = $this->repository->updateStatusAnggota($teamId, $userId, 'confirm');
        $this->assertTrue($result);

        $member = $this->repository->getAnggotaByTeamAndUser($teamId, $userId);
        $this->assertEquals('confirm', $member['status']);
    }

    public function testRemoveAnggota()
    {
        // Setup user and team
        $userData = [
            'username' => 'member3',
            'email' => 'member3@example.com',
            'nama_lengkap' => 'Member User 3',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $stmt = $this->pdo->prepare("INSERT INTO teams (nama_team, deskripsi, lomba_id, leader_id, max_members) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['Test Team 3', 'Desc', 1, $userId, 5]);
        $teamId = $this->pdo->lastInsertId();

        $this->repository->addAnggota($teamId, $userId);

        // Remove
        $result = $this->repository->removeAnggota($teamId, $userId);
        $this->assertTrue($result);

        $member = $this->repository->getAnggotaByTeamAndUser($teamId, $userId);
        $this->assertFalse($member);
    }
}
