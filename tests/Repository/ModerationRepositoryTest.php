<?php

namespace Tests\Repository;

use App\Repository\ModerationRepository;
use App\Repository\EventRepository;
use App\Repository\LostFoundRepository;
use App\Repository\AdminRepository;

class ModerationRepositoryTest extends BaseRepositoryTest
{
    private ModerationRepository $repository;
    private EventRepository $eventRepo;
    private LostFoundRepository $lostFoundRepo;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ModerationRepository();
        $this->eventRepo = new EventRepository();
        $this->lostFoundRepo = new LostFoundRepository();
        $this->userRepo = new AdminRepository();
        
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

        $reflectionUser = new \ReflectionClass($this->userRepo);
        $propertyUser = $reflectionUser->getProperty('connection');
        $propertyUser->setAccessible(true);
        $propertyUser->setValue($this->userRepo, $this->pdo);
    }

    public function testGetPendingItems()
    {
        // Create pending event
        $eventData = [
            'nama_event' => 'Pending Event',
            'deskripsi' => 'Desc',
            'tanggal_mulai' => date('Y-m-d'),
            'lokasi' => 'Loc',
            'status' => 'waiting'
        ];
        $this->eventRepo->createEvent($eventData);

        // Create pending lost item
        // Need user first
        $userData = [
            'username' => 'moduser',
            'email' => 'mod@example.com',
            'nama_lengkap' => 'Mod User',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $lostData = [
            'id_barang' => 'LFMOD',
            'user_id' => $userId,
            'kategori' => 'Elektronik',
            'nama_barang' => 'Pending Item',
            'deskripsi' => 'Desc',
            'lokasi' => 'Loc',
            'no_hp' => '081',
            'status' => 'waiting' // Assuming 'waiting' is a valid status for test, repo checks for 'pending', 'waiting', 'aktif', 'post'
        ];
        // Note: LostFoundRepo createItem takes status directly.
        $this->lostFoundRepo->createItem($lostData);

        $items = $this->repository->getPendingItems();
        $this->assertNotEmpty($items);
        
        $foundEvent = false;
        $foundLost = false;
        foreach ($items as $item) {
            if ($item['title'] === 'Pending Event' && $item['type'] === 'event') {
                $foundEvent = true;
            }
            if ($item['title'] === 'Pending Item' && $item['type'] === 'lost_found') {
                $foundLost = true;
            }
        }
        $this->assertTrue($foundEvent);
        $this->assertTrue($foundLost);
    }

    public function testUpdateStatus()
    {
        // Create pending event
        $eventData = [
            'nama_event' => 'To Approve Event',
            'deskripsi' => 'Desc',
            'tanggal_mulai' => date('Y-m-d'),
            'lokasi' => 'Loc',
            'status' => 'waiting'
        ];
        $eventId = $this->eventRepo->createEvent($eventData);

        // Approve
        $result = $this->repository->updateStatus('event', $eventId, 'approved');
        $this->assertTrue($result);

        $event = $this->eventRepo->getEventById($eventId);
        $this->assertEquals('confirm', $event['status']);
    }
}
