<?php

namespace App\App;

class View
{
    public static function render(string $view, array $model = []): void
    {
        require __DIR__ . '/../View/' . $view . '.php';
    }

    public static function redirect(string $url): void
    {
        if (defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING) {

            echo "Redirect to: " . $url;
            return;
        }

        header('Location: ' . $url);
        exit();
    }
}
