<?php
require_once '../vendor/autoload.php';

use App\App\Router;
use App\Controller\HomeController;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);

$router->run();