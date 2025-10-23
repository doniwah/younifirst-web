<?php

namespace App\Controller;

use App\App\View;
use App\Exception\ValidationException;
use App\Model\UserLoginRequest;
use App\Service\UserService;
use App\Service\SessionService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->sessionService = new SessionService();
    }

    public function login()
    {
        View::render('auth/login', []);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->email = $_POST['email'] ?? '';
        $request->password = $_POST['password'] ?? '';

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->user_id);
            View::redirect('/dashboard');
        } catch (ValidationException $e) {
            View::render('auth/login', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        View::redirect('/users/login');
    }
}
