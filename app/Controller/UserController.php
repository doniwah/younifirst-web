<?php

namespace App\Controller;

use App\App\View;
use App\Config\Database;
use App\Exception\ValidationException;
use App\Model\UserLoginRequest;
use App\Model\UserPasswordUpdateRequest;
use App\Model\UserProfileUpdateRequest;
use App\Model\UserRegisterRequest;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Service\SessionService;
use App\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    public function login()
    {
        View::render('auth/login', [
            "title" => "Login user"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->email = $_POST['email'] ?? '';
        $request->password = $_POST['password'] ?? '';

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::render('component/dashboard/index', [
                'title' => 'Dashboard'
            ]);
        } catch (ValidationException $exception) {
            View::render('auth/login', [
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);
        }
    }


    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }
}