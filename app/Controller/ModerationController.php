<?php

namespace App\Controller;

use App\Repository\ModerationRepository;
use App\Service\SessionService;

class ModerationController
{
    private $moderationRepo;
    private $sessionService;

    public function __construct()
    {
        $this->moderationRepo = new ModerationRepository();
        $this->sessionService = new SessionService();
    }

    public function index()
    {
        // Ensure admin access
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $pendingItems = $this->moderationRepo->getPendingItems();
        
        $title = "Moderasi Konten";
        require __DIR__ . '/../View/admin/moderation.php';
    }

    public function updateStatus()
    {
        // Ensure admin access
        $role = $this->sessionService->getRole();
        if ($role !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? '';
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';

            if ($type && $id && in_array($status, ['approved', 'rejected'])) {
                $result = $this->moderationRepo->updateStatus($type, $id, $status);
                
                if ($result) {
                    header('Location: /admin/moderation?status=success&message=Status updated');
                } else {
                    header('Location: /admin/moderation?status=error&message=Failed to update status');
                }
            } else {
                header('Location: /admin/moderation?status=error&message=Invalid request');
            }
            exit;
        }
    }
}
