<?php

namespace App\Controller;

require_once __DIR__ . '/../Helper/helper.php';

use PHPUnit\Framework\TestCase;
use App\Config\Database;
use App\Domain\Session;
use App\Domain\User;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Service\SessionService;
use App\Controller\UserController;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->userController = new UserController();

        $connection = Database::getConnection();
        $this->sessionRepository = new SessionRepository($connection);
        $this->sessionRepository->deleteAll();

        $this->userRepository = new UserRepository($connection);

        putenv("mode=test");
    }

    public function testLogin()
    {
        $this->userController->login();

        $this->expectOutputRegex("[YouniFirst]");
        $this->expectOutputRegex("[email]");
        $this->expectOutputRegex("[password]");
    }

    public function testPostLoginSuccess()
    {
        $_POST['email'] = 'whyddoni@gmail.com';
        $_POST['password'] = '12121212';

        $this->userController->postLogin();

        $this->expectOutputRegex("[Dashboard]");
    }

    public function testPostLoginValidationError()
    {
        $_POST['email'] = '';
        $_POST['password'] = '';

        $this->userController->postLogin();

        $this->expectOutputRegex("[YouniFirst]");
        $this->expectOutputRegex("[email]");
        $this->expectOutputRegex("[password]");
        $this->expectOutputRegex("[Email dan password tidak boleh kosong]");
    }
}