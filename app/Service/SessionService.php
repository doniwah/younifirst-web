<?php

namespace App\Service;

class SessionService
{
    public static string $SESSION_KEY = 'user_id';
    public static string $SESSION_ROLE_KEY = 'user_role';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create(string $userId, string $role = 'user', string $username = '', string $email = ''): void
    {
        $_SESSION[self::$SESSION_KEY] = $userId;
        $_SESSION[self::$SESSION_ROLE_KEY] = $role;
        $_SESSION['nama'] = $username;
        $_SESSION['email'] = $email;
    }

    public function current(): ?string
    {
        return $_SESSION[self::$SESSION_KEY] ?? null;
    }

    public function getRole(): string
    {
        return $_SESSION[self::$SESSION_ROLE_KEY] ?? 'user';
    }

    public function destroy(): void
    {
        unset($_SESSION[self::$SESSION_KEY]);
        session_destroy();
    }
}
