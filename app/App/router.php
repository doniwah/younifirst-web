<?php

namespace App\App;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/');
        if ($url === '') $url = '/';

        $callback = $this->routes[$method][$url] ?? false;

        if ($callback === false) {
            http_response_code(404);
            echo "404 - Page Not Found";
            return;
        }

        if (is_array($callback)) {
            [$controller, $method] = $callback;
            $controller = new $controller;
            echo call_user_func([$controller, $method]);
        } else {
            echo call_user_func($callback);
        }
    }
}