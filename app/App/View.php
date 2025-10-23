<?php

namespace App\App;

class View
{
    public static function render(string $view, $model)
    {
        require __DIR__ . '/../View/' . $view . '.php';
    }

    public static function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }
}