<?php 

namespace App\Controller;

class ForumController
{
    public function forum()
    {
        session_start();

        if(!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        ob_start();
        include __DIR__. '/../view/component/forum/index.php';
        return ob_get_clean();
    }
}
?>