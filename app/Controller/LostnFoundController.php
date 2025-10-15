<?php 

namespace App\Controller;

class LostnFoundController
{
    public function lost_found()
    {
        session_start();

        if(!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        ob_start();
        include __DIR__. '/../view/component/lost&found/index.php';
        return ob_get_clean();
    }
}
?>