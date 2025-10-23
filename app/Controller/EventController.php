<?php

namespace App\Controller;

class EventController
{
    public function event()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        ob_start();
        include __DIR__ . '/../view/component/event/index.php';
        return ob_get_clean();
    }
}
