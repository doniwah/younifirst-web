<?php

namespace App\Service;

use App\Config\Database;
use App\Domain\User;
use App\Exception\ValidationException;
use App\Model\UserLoginRequest;
use App\Model\UserLoginResponse;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection('prod'));
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        if (trim($request->email) === '' || trim($request->password) === '') {
            throw new ValidationException('Email dan Password wajib diisi');
        }

        $user = $this->userRepository->findByEmail($request->email);
        if ($user == null) {
            throw new ValidationException('Email tidak ditemukan');
        }

        if (!password_verify($request->password, $user->password)) {
            throw new ValidationException('Password salah');
        }

        $response = new UserLoginResponse();
        $response->user = $user;
        return $response;
    }
}
