<?php

namespace App\Controller;

use App\App\View;
use App\Service\SessionService;
use App\Repository\EventRepository;

class EventController
{
    private SessionService $session;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->eventRepository = new EventRepository();
    }

    public function event()
    {
        View::render('component/event/index', [
            'title' => 'Event Management',
            'user' => $this->session->current(),
            'events' => $this->eventRepository->getAllEvents()
        ]);
    }
}