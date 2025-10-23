<?php

namespace App\Repository;

use PHPUnit\Framework\TestCase;
use App\Config\Database;
use App\Domain\User;

class UserRepositoryTest extends TestCase
{

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();

        $this->userRepository = new UserRepository(Database::getConnection());
    }


    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findByEmail("notfound");
        self::assertNull($user);
    }
}