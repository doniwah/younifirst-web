<?php

namespace App\Controller;

use App\App\View;
use App\Config\Database;
use App\Repository\UserRepository;
use App\Service\SessionService;

class SettingsController
{
    private UserRepository $userRepository;
    private SessionService $sessionService;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService();
    }

    public function index()
    {
        $userId = $this->sessionService->current();
        if (!$userId) {
            View::redirect('/users/login');
            return;
        }

        $user = $this->userRepository->findById($userId);
        
        View::render('settings/index', [
            'title' => 'Settings',
            'user' => $user
        ]);
    }

    public function updateProfile()
    {
        $userId = $this->sessionService->current();
        if (!$userId) {
            View::redirect('/users/login');
            return;
        }

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            View::redirect('/users/login');
            return;
        }

        $user->nama_lengkap = $_POST['nama_lengkap'] ?? $user->nama_lengkap;
        $user->angkatan = $_POST['angkatan'] ?? $user->angkatan;
        $user->tgl_lahir = $_POST['tgl_lahir'] ?? $user->tgl_lahir;
        
        // Handle notification toggle if sent via form (optional fallback)
        if (isset($_POST['is_notification_active'])) {
            $user->is_notification_active = (bool)$_POST['is_notification_active'];
        }

        $this->userRepository->update($user);
        
        View::redirect('/settings');
    }

    public function toggleNotification()
    {
        header('Content-Type: application/json');
        
        $userId = $this->sessionService->current();
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $isActive = $data['is_active'] ?? true;

        $user->is_notification_active = (bool)$isActive;
        $this->userRepository->update($user);

        echo json_encode(['status' => 'success', 'is_active' => $user->is_notification_active]);
    }
}
