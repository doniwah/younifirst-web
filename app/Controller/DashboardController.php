<?php

namespace App\Controller;

use App\App\View;
use App\Repository\DashboardRepository;

class DashboardController
{
    public function index()
    {
        $repo = new DashboardRepository();

        View::render('component/dashboard/index', [
            'title' => 'Dashboard',
            'stat_kompetisi'   => $repo->getStatKompetisi(),
            'stat_lost'        => $repo->getStatLost(),
            'stat_event'       => $repo->getStatEvent(),
            'kompetisi_latest' => $repo->getLatestKompetisi(),
            'lost_latest'      => $repo->getLatestLost(),
            'events_latest'    => $repo->getLatestEvent(),
        ]);
    }
}
