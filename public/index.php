<?php
require_once '../vendor/autoload.php';
require_once '../app/Models/Database.php';

use App\App\Router;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\DashboardController;
use App\Controller\KompetisiController;
use App\Models\Database;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'dashboard']);
$router->get('/kompetisi', [KompetisiController::class, 'kompetisi']);
$router->run();
