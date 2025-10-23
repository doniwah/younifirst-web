<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use App\Config\Database;
use App\Domain\User;
use App\Exception\ValidationException;
use App\Model\UserLoginRequest;
use App\Model\UserPasswordUpdateRequest;
use App\Model\UserProfileUpdateRequest;
use App\Model\UserRegisterRequest;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->userService = new UserService($this->userRepository);
        $this->sessionRepository = new SessionRepository($connection);

        $this->sessionRepository->deleteAll();
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->username = "Eko";
        $user->password = password_hash("eko", PASSWORD_BCRYPT);

        $this->expectException(ValidationException::class);

        $request = new UserLoginRequest();
        $request->email = "whyddoni@gmail.com";
        $request->password = "12121212";

        $response = $this->userService->login($request);

        self::assertEquals($request->email, $response->user->email);
        self::assertTrue(password_verify($request->password, $response->user->password));
    }
}