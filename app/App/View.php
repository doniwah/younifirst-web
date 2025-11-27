<?php

namespace App\App;

class View
{
    public static function render(string $view, array $model = []): void
    {
        ob_start();
        extract($model);

        // Path yang benar: naik 1 level ke app/, lalu masuk ke View/
        $viewPath = __DIR__ . '/../View/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: " . $viewPath);
        }

        require $viewPath;

        $output = ob_get_clean();

        echo $output;
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