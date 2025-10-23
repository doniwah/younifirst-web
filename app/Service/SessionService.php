<?php

namespace App\Service;

use App\Config\Database;
use App\Domain\Session;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = 'X-YOUNIFIRST-SESSION';
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $connection = Database::getConnection('prod');
        $this->sessionRepository = new SessionRepository($connection);
        $this->userRepository = new UserRepository($connection);
    }

    public function create(string $userId): Session
    {
        // Hapus cookie lama jika ada
        if (isset($_COOKIE[self::$COOKIE_NAME])) {
            setcookie(self::$COOKIE_NAME, '', time() - 3600, "/");
            unset($_COOKIE[self::$COOKIE_NAME]);
        }

        // Buat session baru
        $session = new Session();
        $session->id = uniqid('sess_');
        $session->userId = $userId;

        // Simpan ke DB
        $this->sessionRepository->save($session);

        // Set cookie baru
        setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24 * 30), "/");

        return $session;
    }

    public function current(): ?object
    {
        if (!isset($_COOKIE[self::$COOKIE_NAME])) {
            return null;
        }

        $sessionId = $_COOKIE[self::$COOKIE_NAME];
        $session = $this->sessionRepository->findById($sessionId);
        if ($session == null) {
            return null;
        }

        return $this->userRepository->findById($session->userId);
    }

    public function destroy(): void
    {
        if (isset($_COOKIE[self::$COOKIE_NAME])) {
            $sessionId = $_COOKIE[self::$COOKIE_NAME];
            $this->sessionRepository->deleteById($sessionId);
            setcookie(self::$COOKIE_NAME, '', time() - 3600, "/");
        }
    }
}
