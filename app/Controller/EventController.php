<?php

namespace App\Controller;

use App\App\View;
use App\Service\SessionService;

class EventController
{
    private SessionService $session;

    public function __construct()
    {
        $this->session = new SessionService();
    }

    public function event()
    {
        View::render('component/event/index', [
            'title' => 'Kompetisi',
            'user' => $this->session->current()
        ]);
    }
}