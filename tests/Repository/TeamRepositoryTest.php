<?php

namespace Tests\Repository;

use App\Repository\TeamRepository;
use App\Repository\AdminRepository;

class TeamRepositoryTest extends BaseRepositoryTest
{
    private TeamRepository $repository;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TeamRepository();
        $this->userRepo = new AdminRepository();
        
        // Inject PDO
        $reflection = new \ReflectionClass($this->repository);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->repository, $this->pdo);

        $reflectionUser = new \ReflectionClass($this->userRepo);
        $propertyUser = $reflectionUser->getProperty('connection');
        $propertyUser->setAccessible(true);
        $propertyUser->setValue($this->userRepo, $this->pdo);
    }

    public function testCreateTeam()
    {
        $data = [
            'nama_team' => 'Test Team',
            'nama_kegiatan' => 'Hackathon',
            'max_anggota' => 5,
            'role_required' => 'Frontend',
            'keterangan_tambahan' => 'Note',
            'status' => 'waiting',
            'tenggat_join' => date('Y-m-d', strtotime('+7 days')),
            'deskripsi_anggota' => 'Desc',
            'penyelenggara' => 'Organizer',
            'link_postingan' => 'http://example.com',
            'ketentuan' => 'Rules'
        ];

        $teamId = $this->repository->createTeam($data);
        $this->assertNotFalse($teamId);
        $this->assertStringStartsWith('T', $teamId);

        $team = $this->repository->getTeamById($teamId);
        $this->assertEquals($data['nama_team'], $team['nama_team']);
    }

    public function testUpdateTeam()
    {
        $data = [
            'nama_team' => 'Test Team',
            'nama_kegiatan' => 'Hackathon',
            'max_anggota' => 5,
            'role_required' => 'Frontend',
            'keterangan_tambahan' => 'Note',
            'status' => 'waiting',
            'tenggat_join' => date('Y-m-d', strtotime('+7 days'))
        ];
        $teamId = $this->repository->createTeam($data);

        $updateData = [
            'nama_team' => 'Updated Team',
            'max_anggota' => 10
        ];
        $result = $this->repository->updateTeam($teamId, $updateData);
        $this->assertTrue($result);

        $team = $this->repository->getTeamById($teamId);
        $this->assertEquals('Updated Team', $team['nama_team']);
        $this->assertEquals(10, $team['max_anggota']);
    }

    public function testGetTeamsWithFilters()
    {
        $data = [
            'nama_team' => 'Filter Team',
            'nama_kegiatan' => 'Hackathon',
            'max_anggota' => 5,
            'role_required' => 'Frontend',
            'keterangan_tambahan' => 'Note',
            'status' => 'confirm',
            'tenggat_join' => date('Y-m-d', strtotime('+7 days'))
        ];
        $this->repository->createTeam($data);

        $filters = [
            'search' => 'Filter',
            'status' => 'confirm'
        ];
        $result = $this->repository->getTeamsWithFilters($filters);
        
        $this->assertGreaterThan(0, $result['total_items']);
        $this->assertEquals('Filter Team', $result['team'][0]['nama_team']);
    }
}
