<?php

namespace App\Controller;

use App\App\View;

class DashboardController
{
    public function index()
    {
        View::render('component/dashboard/index', [
            'title' => 'Dashboard',
        ]);
    }
}
