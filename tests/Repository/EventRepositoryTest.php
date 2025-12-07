<?php

namespace Tests\Repository;

use App\Repository\EventRepository;
use App\Repository\AdminRepository;

class EventRepositoryTest extends BaseRepositoryTest
{
    private EventRepository $repository;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EventRepository();
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

    public function testCreateEvent()
    {
        $data = [
            'nama_event' => 'Test Event',
            'deskripsi' => 'Test Description',
            'tanggal_mulai' => date('Y-m-d'),
            'tanggal_selesai' => date('Y-m-d', strtotime('+1 day')),
            'lokasi' => 'Test Location',
            'organizer' => 'Organizer',
            'kapasitas' => 100,
            'kategori' => 'Seminar',
            'harga' => 0,
            'status' => 'waiting'
        ];

        $eventId = $this->repository->createEvent($data);
        $this->assertNotFalse($eventId);
        $this->assertStringStartsWith('E', $eventId);

        $event = $this->repository->getEventById($eventId);
        $this->assertEquals($data['nama_event'], $event['nama_event']);
    }

    public function testUpdateEvent()
    {
        $data = [
            'nama_event' => 'Test Event',
            'deskripsi' => 'Test Description',
            'tanggal_mulai' => date('Y-m-d'),
            'lokasi' => 'Test Location',
            'organizer' => 'Organizer',
            'kapasitas' => 100
        ];
        $eventId = $this->repository->createEvent($data);

        $updateData = [
            'nama_event' => 'Updated Event',
            'kapasitas' => 200
        ];
        $result = $this->repository->updateEvent($eventId, $updateData);
        $this->assertTrue($result);

        $event = $this->repository->getEventById($eventId);
        $this->assertEquals('Updated Event', $event['nama_event']);
        $this->assertEquals(200, $event['kapasitas']);
    }

    public function testRegisterForEvent()
    {
        // Create event
        $eventData = [
            'nama_event' => 'Test Event',
            'deskripsi' => 'Test Description',
            'tanggal_mulai' => date('Y-m-d'),
            'lokasi' => 'Test Location',
            'organizer' => 'Organizer',
            'kapasitas' => 10,
            'status' => 'confirm'
        ];
        $eventId = $this->repository->createEvent($eventData);

        // Create user
        $userData = [
            'username' => 'participant',
            'email' => 'participant@example.com',
            'nama_lengkap' => 'Participant',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $result = $this->repository->registerForEvent($eventId, $userId);
        $this->assertTrue($result);

        $isRegistered = $this->repository->isUserRegistered($eventId, $userId);
        $this->assertTrue($isRegistered);

        // Check capacity update
        $event = $this->repository->getEventById($eventId);
        $this->assertEquals(1, $event['peserta_terdaftar']);
    }
}
