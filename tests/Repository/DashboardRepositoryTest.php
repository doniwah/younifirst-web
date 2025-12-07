<?php

namespace Tests\Repository;

use App\Repository\DashboardRepository;
use App\Repository\EventRepository;
use App\Repository\LostFoundRepository;

class DashboardRepositoryTest extends BaseRepositoryTest
{
    private DashboardRepository $repository;
    private EventRepository $eventRepo;
    private LostFoundRepository $lostFoundRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DashboardRepository();
        $this->eventRepo = new EventRepository();
        $this->lostFoundRepo = new LostFoundRepository();
        
        // Inject PDO
        $reflection = new \ReflectionClass($this->repository);
        $property = $reflection->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($this->repository, $this->pdo);

        $reflectionEvent = new \ReflectionClass($this->eventRepo);
        $propertyEvent = $reflectionEvent->getProperty('db');
        $propertyEvent->setAccessible(true);
        $propertyEvent->setValue($this->eventRepo, $this->pdo);

        $reflectionLost = new \ReflectionClass($this->lostFoundRepo);
        $propertyLost = $reflectionLost->getProperty('db');
        $propertyLost->setAccessible(true);
        $propertyLost->setValue($this->lostFoundRepo, $this->pdo);
    }

    public function testGetStatEvent()
    {
        // Create an event
        $data = [
            'nama_event' => 'Test Event',
            'deskripsi' => 'Test Description',
            'tanggal_mulai' => date('Y-m-d'),
            'tanggal_selesai' => date('Y-m-d', strtotime('+1 day')),
            'lokasi' => 'Test Location',
            'poster_event' => 'test.jpg',
            'kategori' => 'Seminar',
            'harga' => 0
        ];
        $this->eventRepo->createEvent($data);

        $count = $this->repository->getStatEvent();
        $this->assertGreaterThan(0, $count);
    }

    public function testGetStatLost()
    {
        // Create lost item
        $data = [
            'nama_barang' => 'Lost Item',
            'deskripsi' => 'Description',
            'lokasi' => 'Location',
            'tanggal' => date('Y-m-d'),
            'kategori' => 'Elektronik',
            'status' => 'hilang',
            'foto_barang' => 'test.jpg',
            'user_id' => 'USR001' // Assuming user exists or FK check is disabled/mocked
        ];
        // Note: We might need to create a user first if FK constraint exists.
        // For simplicity, assuming we can insert or ignoring FK for this specific unit test if tables are isolated or using mocks.
        // But better to use real flow.
        
        // Let's create a user first to be safe
        $userRepo = new \App\Repository\AdminRepository();
        $reflectionUser = new \ReflectionClass($userRepo);
        $propertyUser = $reflectionUser->getProperty('connection');
        $propertyUser->setAccessible(true);
        $propertyUser->setValue($userRepo, $this->pdo);
        
        $userData = [
            'username' => 'lostuser',
            'email' => 'lost@example.com',
            'nama_lengkap' => 'Lost User',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $userRepo->createUser($userData);
        $data['user_id'] = $userId;

        $this->lostFoundRepo->create($data);

        $count = $this->repository->getStatLost();
        $this->assertGreaterThan(0, $count);
    }

    public function testGetUpcomingEvents()
    {
        // Create upcoming event
        $data = [
            'nama_event' => 'Upcoming Event',
            'deskripsi' => 'Test Description',
            'tanggal_mulai' => date('Y-m-d', strtotime('+1 day')),
            'tanggal_selesai' => date('Y-m-d', strtotime('+2 days')),
            'lokasi' => 'Future Location',
            'poster_event' => 'future.jpg',
            'kategori' => 'Workshop',
            'harga' => 50000
        ];
        $this->eventRepo->createEvent($data);

        $events = $this->repository->getUpcomingEvents();
        $this->assertNotEmpty($events);
        $found = false;
        foreach ($events as $event) {
            if ($event['title'] === 'Upcoming Event') {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
}
