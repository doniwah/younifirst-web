<?php

namespace App\Controller;

class HomeController
{
    public function index()
    {
        include '../app/view/index.php';
        return ob_get_clean();
    }
}