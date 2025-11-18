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

    public function apiLogin()
    {
        header('Content-Type: application/json');

        $request = new UserLoginRequest();
        $request->email = $_POST['email'] ?? '';
        $request->password = $_POST['password'] ?? '';

        try {
            $response = $this->userService->login($request);

            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $response->user->user_id,
                    'email' => $response->user->email
                ]
            ]);
        } catch (ValidationException $e) {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
