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
use App\Controller\Api\KompetisiApiController;
use App\Controller\Api\TeamApiController;
use App\Config\Database;
use App\App\Router;
use App\Middleware\MustLoginMiddleware;
use App\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/team', TeamController::class, 'index', []);
//User Controller
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('POST', '/api/login', UserController::class, 'apiLogin');
Router::add('GET', '/dashboard', DashboardController::class, 'index', [MustLoginMiddleware::class]);

Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);

// Team Routes
Router::add('GET', '/team', TeamController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/team/create', TeamController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/team/store', TeamController::class, 'store', [MustLoginMiddleware::class]);
Router::add('GET', '/team/edit/{id}', TeamController::class, 'edit', [MustLoginMiddleware::class]);
Router::add('POST', '/team/update/{id}', TeamController::class, 'update', [MustLoginMiddleware::class]);
Router::add('DELETE', '/team/delete/{id}', TeamController::class, 'delete', [MustLoginMiddleware::class]);

// Kompetisi Routes
Router::add('GET', '/kompetisi', KompetisiController::class, 'index', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create', KompetisiController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create-lomba', KompetisiController::class, 'createLomba', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create-team', KompetisiController::class, 'createTeam', [MustLoginMiddleware::class]);
Router::add('GET', '/kompetisi/{id}', KompetisiController::class, 'detail', [MustLoginMiddleware::class]);

//API KOMPETISI
Router::add('GET', '/api/kompetisi', KompetisiApiController::class, 'index');
Router::add('GET', '/api/kompetisi/{id}', KompetisiApiController::class, 'detail');
Router::add('POST', '/api/kompetisi/create', KompetisiApiController::class, 'create');
Router::add('POST', '/api/kompetisi/create-lomba', KompetisiApiController::class, 'createLomba');
Router::add('POST', '/api/kompetisi/create-team', KompetisiApiController::class, 'createTeam');

// Lost & Found Routes
Router::add('GET', '/lost_found', LostnFoundController::class, 'lost_found', [MustLoginMiddleware::class]);
Router::add('POST', '/lost_found/create', LostnFoundController::class, 'create', [MustLoginMiddleware::class]);

// Event Routes
Router::add('GET', '/event', EventController::class, 'event', [MustLoginMiddleware::class]);

// Forum Routes
Router::add('GET', '/forum', ForumController::class, 'forum', [MustLoginMiddleware::class]);
Router::add('GET', '/forum/chat', ForumController::class, 'chat', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/send-message', ForumController::class, 'sendMessage', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/delete-message', ForumController::class, 'deleteMessage', [MustLoginMiddleware::class]);

// Team API Routes
Router::add('GET', '/api/teams', TeamApiController::class, 'getAllTeams', []);
Router::add('GET', '/api/teams/{id}', TeamApiController::class, 'getTeam', []);
Router::add('POST', '/api/teams', TeamApiController::class, 'createTeam', [MustLoginMiddleware::class]);
Router::add('PUT', '/api/teams/{id}', TeamApiController::class, 'updateTeam', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/teams/{id}', TeamApiController::class, 'deleteTeam', [MustLoginMiddleware::class]);
Router::add('GET', '/api/teams/{teamId}/members', TeamApiController::class, 'getTeamMembers', []);
Router::add('POST', '/api/teams/{teamId}/members', TeamApiController::class, 'addTeamMember', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/teams/{teamId}/members/{userId}', TeamApiController::class, 'removeTeamMember', [MustLoginMiddleware::class]);
Router::add('PUT', '/api/teams/{teamId}/members/{userId}/role', TeamApiController::class, 'updateTeamMemberRole', [MustLoginMiddleware::class]);
Router::add('GET', '/api/teams/search', TeamApiController::class, 'searchTeams', []);
Router::add('GET', '/api/teams/user/{userId}', TeamApiController::class, 'getUserTeams', []);
Router::add('GET', '/api/teams/competition/{competitionId}', TeamApiController::class, 'getTeamsByCompetition', []);

Router::run();