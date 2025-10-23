<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Helper/helper.php';

class HelperTest extends TestCase
{
    public function testHeaderFunctionOutputsCorrectly()
    {
        ob_start();
        \App\header('X-Test: OK');
        $result = ob_get_clean();

        $this->assertEquals('X-Test: OK', $result);
    }

    public function testSetcookieFunctionOutputsCorrectly()
    {
        ob_start();
        \App\Service\setcookie('session', 'abc123');
        $result = ob_get_clean();

        $this->assertEquals('session: abc123', $result);
    }
}
