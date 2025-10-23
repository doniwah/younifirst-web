<?php

namespace App\Service;

use App\Config\Database;
use App\Domain\User;
use App\Exception\ValidationException;
use App\Model\UserLoginRequest;
use App\Model\UserLoginResponse;
use App\Model\UserPasswordUpdateRequest;
use App\Model\UserPasswordUpdateResponse;
use App\Model\UserProfileUpdateRequest;
use App\Model\UserProfileUpdateResponse;
use App\Model\UserRegisterRequest;
use App\Model\UserRegisterResponse;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findByEmail($request->email);
        if ($user == null) {
            throw new ValidationException("Email or password is wrong");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Email or password is wrong");
        }
    }

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if (
            $request->email == null || $request->password == null ||
            trim($request->email) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Email dan password tidak boleh kosong");
        }
    }
}