<?php

namespace Tests\Repository;

use App\Repository\LostFoundRepository;
use App\Repository\AdminRepository;

class LostFoundRepositoryTest extends BaseRepositoryTest
{
    private LostFoundRepository $repository;
    private AdminRepository $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new LostFoundRepository();
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

    public function testCreateItem()
    {
        // Create user
        $userData = [
            'username' => 'lostuser',
            'email' => 'lost@example.com',
            'nama_lengkap' => 'Lost User',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $data = [
            'id_barang' => 'LF001',
            'user_id' => $userId,
            'kategori' => 'Elektronik',
            'nama_barang' => 'Laptop',
            'deskripsi' => 'Lost my laptop',
            'lokasi' => 'Library',
            'no_hp' => '08123456789',
            'status' => 'hilang'
        ];

        $result = $this->repository->createItem($data);
        $this->assertEquals('LF001', $result);

        $item = $this->repository->getItemById('LF001');
        $this->assertEquals('Laptop', $item['nama_barang']);
    }

    public function testUpdateItem()
    {
        // Create user and item
        $userData = [
            'username' => 'lostuser2',
            'email' => 'lost2@example.com',
            'nama_lengkap' => 'Lost User 2',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $data = [
            'id_barang' => 'LF002',
            'user_id' => $userId,
            'kategori' => 'Elektronik',
            'nama_barang' => 'Phone',
            'deskripsi' => 'Lost phone',
            'lokasi' => 'Canteen',
            'no_hp' => '08123456789',
            'status' => 'hilang'
        ];
        $this->repository->createItem($data);

        $updateData = [
            'nama_barang' => 'Smartphone',
            'status' => 'menemukan'
        ];
        $result = $this->repository->updateItem('LF002', $updateData);
        $this->assertTrue($result);

        $item = $this->repository->getItemById('LF002');
        $this->assertEquals('Smartphone', $item['nama_barang']);
        $this->assertEquals('menemukan', $item['status']);
    }

    public function testSearchItems()
    {
        // Create user and item
        $userData = [
            'username' => 'lostuser3',
            'email' => 'lost3@example.com',
            'nama_lengkap' => 'Lost User 3',
            'password' => 'password',
            'role' => 'mahasiswa'
        ];
        $userId = $this->userRepo->createUser($userData);

        $data = [
            'id_barang' => 'LF003',
            'user_id' => $userId,
            'kategori' => 'Lainnya',
            'nama_barang' => 'UniqueItemName',
            'deskripsi' => 'Description',
            'lokasi' => 'Location',
            'no_hp' => '08123456789',
            'status' => 'hilang'
        ];
        $this->repository->createItem($data);

        $results = $this->repository->searchItems('UniqueItemName');
        $this->assertCount(1, $results);
        $this->assertEquals('LF003', $results[0]['id_barang']);
    }
}
