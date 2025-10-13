<?php

namespace App\Controller;

class DashboardController
{
    public function dashboard()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        ob_start();
        include __DIR__ . '/../view/component/dashboard.php';
        return ob_get_clean();
    }
}
