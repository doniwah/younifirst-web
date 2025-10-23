<?php

namespace App\Controller;

use App\App\View;

class HomeController
{
    public function index()
    {
        View::render('index', ['title' => 'YouniFirst']);
    }
}