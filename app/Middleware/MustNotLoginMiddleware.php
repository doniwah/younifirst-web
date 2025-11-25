<?php

namespace App\Middleware;

use App\Service\SessionService;
use App\App\View;

class MustNotLoginMiddleware implements Middleware
{
    public function before(): void
    {
        $session = new SessionService();
        if ($session->current() !== null) {
            View::redirect('/dashboard');
        }
    }
}
