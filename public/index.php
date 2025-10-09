<?php
require_once '../vendor/autoload.php';

use App\App\Router;
use App\Controller\HomeController;
use App\Controller\LoginController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [LoginController::class, 'login']);
$router->run();