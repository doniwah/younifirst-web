<?php

namespace App\Controller;

use App\Model\Team;
use App\Model\TeamMember;
use App\Model\Competition;
use App\App\View;
use App\Service\SessionService;

class TeamController
{
    public function index()
    {
        View::render('component/team/index', [
            'title' => 'team'
        ]);
    }
}