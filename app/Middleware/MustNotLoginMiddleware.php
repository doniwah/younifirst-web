<?php

namespace App\Middleware;

use App\Service\SessionService;
use App\App\View;

class MustNotLoginMiddleware implements Middleware
{
    public function before(): void
    {
        $sessionService = new SessionService();
        $user = $sessionService->current();

        if ($user !== null) {
            View::redirect('/dashboard');
        }
    }
}
