<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class ViewTest extends TestCase
{
    public function testRenderViewFileExists()
    {
        $viewClassPath = __DIR__ . '/../../app/App/View.php';
        $this->assertFileExists($viewClassPath);
    }

    public function testRenderOutputsViewContent()
    {
        ob_start();
        \App\App\View::render('auth/login', ['error' => 'Oops test']);
        $output = ob_get_clean();

        $this->assertStringContainsString('Oops test', $output);
    }
}
