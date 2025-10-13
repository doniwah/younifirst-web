<?php

namespace App\Controller;

class KompetisiController
{
    public function kompetisi()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        ob_start();
        include __DIR__ . '/../view/component/kompetisi/index.php';
        return ob_get_clean();
    }
}
