<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class MustNotLoginMiddlewareTest extends TestCase
{
    public function testBeforeRedirectsIfLoggedIn()
    {
        $_COOKIE[\App\Service\SessionService::$COOKIE_NAME] = '68f9ca46a7002';

        $mw = new \App\Middleware\MustNotLoginMiddleware();

        // Tangkap output
        ob_start();
        try {
            $mw->before();
        } catch (\Throwable $e) {
            // abaikan exit() jika ada
        }
        $output = ob_get_clean();

        // Anggap redirect berhasil kalau ada teks "Redirect" atau "Location"
        $redirected = stripos($output, 'redirect') !== false || stripos($output, 'location') !== false;

        $this->assertTrue($redirected, 'Middleware seharusnya melakukan redirect saat user login.');
    }
}
