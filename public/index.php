<?php
require_once '../vendor/autoload.php';
require_once '../app/Models/Database.php';

use App\App\Router;
use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\DashboardController;
use App\Controller\EventController;
use App\Controller\KompetisiController;
use App\Controller\LostnFoundController;
use App\Controller\ForumController;
use App\Models\Database;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'dashboard']);


$router->get('/kompetisi', [KompetisiController::class, 'index']);
$router->post('/kompetisi/create', [KompetisiController::class, 'create']);
$router->get('/kompetisi/{id}', [KompetisiController::class, 'detail']);

$router->post('/kompetisi/{id}/approve', [KompetisiController::class, 'approve']);
$router->post('/kompetisi/{id}/reject', [KompetisiController::class, 'reject']);

$router->get('/lost_found', [LostnFoundController::class, 'lost_found']);
$router->get('/event', [EventController::class, 'event']);
$router->get('/forum', [ForumController::class, 'forum']);
$router->run();