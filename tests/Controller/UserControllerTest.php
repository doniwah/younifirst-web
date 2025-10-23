<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class UserControllerTest extends TestCase
{
    public function testLoginMethodExists()
    {
        $controller = new \App\Controller\UserController();
        $this->assertTrue(method_exists($controller, 'login'));
    }

    public function testLoginActionRendersLoginView()
    {
        ob_start();
        $controller = new \App\Controller\UserController();
        $controller->login(); // seharusnya memanggil View::render('auth/login', ...)
        $output = ob_get_clean();

        $this->assertStringContainsString('<form', $output); // asumsi form ada di view
    }
}