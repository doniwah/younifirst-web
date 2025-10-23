<?php
require_once __DIR__ . '/../vendor/autoload.php';


use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\DashboardController;
use App\Controller\EventController;
use App\Controller\KompetisiController;
use App\Controller\LostnFoundController;
use App\Controller\ForumController;
use App\Controller\TeamController;
use App\Controller\UserController;
use App\Config\Database;
use App\App\Router;
use App\Middleware\MustLoginMiddleware;
use App\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');


Router::add('GET', '/', HomeController::class, 'index', []);

//User Controller
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);


Router::add('GET', '/dashboard', DashboardController::class, 'index', [MustLoginMiddleware::class]);

Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
// Router::add('GET', '/login', LoginController::class, 'login', []);
// Router::add('POST', '/login', LoginController::class, 'login', []);
// Router::add('GET', '/logout', LoginController::class, 'logout', []);

// $router->get('/dashboard', [DashboardController::class, 'dashboard']);

// $router->get('/kompetisi', [KompetisiController::class, 'index']);
// $router->post('/kompetisi/create', [KompetisiController::class, 'create']);
// $router->get('/kompetisi/{id}', [KompetisiController::class, 'detail']);

// $router->post('/kompetisi/{id}/approve', [KompetisiController::class, 'approve']);
// $router->post('/kompetisi/{id}/reject', [KompetisiController::class, 'reject']);

// $router->get('/lost_found', [LostnFoundController::class, 'lost_found']);
// $router->post('/lost_found/create', [LostnFoundController::class, 'create']);


// $router->get('/event', [EventController::class, 'event']);

// $router->get('/forum', [ForumController::class, 'forum']);
// $router->get('/forum/chat', [ForumController::class, 'chat']);
// $router->post('/forum/send-message', [ForumController::class, 'sendMessage']);
// $router->post('/forum/delete-message', [ForumController::class, 'deleteMessage']);

// // Team Management - GANTI DENGAN FORMAT YANG BENAR
// $router->post('/team/create', [TeamController::class, 'create']);
// $router->get('/team/list', [TeamController::class, 'index']);
// $router->get('/team/detail/{id}', [TeamController::class, 'detail']);
// $router->post('/team/{id}/delete', [TeamController::class, 'delete']);

// // Team Request Routes
// $router->post('/team/request', [TeamController::class, 'submitRequest']);
// $router->get('/team/requests/pending', [TeamController::class, 'getPendingRequests']);
// $router->get('/team/{id}/requests', [TeamController::class, 'getTeamRequests']);
// $router->get('/team/request/{id}/detail', [TeamController::class, 'requestDetail']);
// $router->post('/team/{id}/approve', [TeamController::class, 'approve']);
// $router->post('/team/{id}/reject', [TeamController::class, 'reject']);

Router::run();
