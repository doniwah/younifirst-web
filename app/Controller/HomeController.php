<?php

namespace App\Controller;

class HomeController
{
    public function index()
    {
        $title = "Halaman Utama";
        $message = "Selamat datang di Younifirst Web buatan Ridho!";

        ob_start();
        include '../app/view/index.php';
        return ob_get_clean();
    }
}