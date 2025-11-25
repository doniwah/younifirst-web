<?php

namespace App\Service;

class SessionService
{
    public static string $SESSION_KEY = 'user_id';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create(string $userId): void
    {
        $_SESSION[self::$SESSION_KEY] = $userId;
    }

    public function current(): ?string
    {
        return $_SESSION[self::$SESSION_KEY] ?? null;
    }

    public function destroy(): void
    {
        unset($_SESSION[self::$SESSION_KEY]);
        session_destroy();
    }
}
