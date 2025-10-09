<?php

namespace App\Controller;

class LoginController
{
    public function login()
    {
        ob_start();
        include '../app/view/auth/login.php';
        return ob_get_clean();
    }
}