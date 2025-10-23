<?php

namespace App;

use App\App\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('component/dashboard/index', [
            'title' => 'Dashboard'
        ]);

        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
    }
}