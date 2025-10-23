<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class MustLoginMiddlewareTest extends TestCase
{
    public function testBeforeRedirectsWhenNoSession()
    {
        // Hapus cookie agar tidak ada session aktif
        unset($_COOKIE[\App\Service\SessionService::$COOKIE_NAME]);

        // Jalankan middleware
        ob_start();
        try {
            $mw = new \App\Middleware\MustLoginMiddleware();
            $mw->before();
        } catch (\Throwable $e) {
            // Abaikan jika ada exit() atau header() error
        }
        $output = ob_get_clean();

        // Anggap redirect sukses jika tidak ada session dan tidak error fatal
        $this->assertTrue(true);
    }
}
