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
use App\Controller\Api\EventApiController;
use App\Controller\Api\LostFoundApiController;
use App\Config\Database;
use App\App\Router;
use App\Middleware\MustLoginMiddleware;
use App\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/dashboard', DashboardController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);

// Team Routes
Router::add('GET', '/team', TeamController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/team/create', TeamController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/team/store', TeamController::class, 'store', [MustLoginMiddleware::class]);
Router::add('GET', '/team/edit/([a-zA-Z0-9]+)', TeamController::class, 'edit', [MustLoginMiddleware::class]);
Router::add('POST', '/team/update/([a-zA-Z0-9]+)', TeamController::class, 'update', [MustLoginMiddleware::class]);
Router::add('DELETE', '/team/delete/([a-zA-Z0-9]+)', TeamController::class, 'delete', [MustLoginMiddleware::class]);
Router::add('POST', '/team/upload-poster/([a-zA-Z0-9]+)', TeamController::class, 'uploadPoster', [MustLoginMiddleware::class]);

// Kompetisi Routes
Router::add('GET', '/kompetisi', KompetisiController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/kompetisi/create', KompetisiController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create', KompetisiController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create-lomba', KompetisiController::class, 'createLomba', [MustLoginMiddleware::class]);
Router::add('POST', '/kompetisi/create-team', KompetisiController::class, 'createTeam', [MustLoginMiddleware::class]);
Router::add('GET', '/kompetisi/([a-zA-Z0-9]+)', KompetisiController::class, 'detail', [MustLoginMiddleware::class]);

// API Kompetisi Routes
Router::add('GET', '/api/kompetisi', KompetisiApiController::class, 'index');
Router::add('GET', '/api/kompetisi/([a-zA-Z0-9]+)', KompetisiApiController::class, 'detail');
Router::add('POST', '/api/kompetisi/create', KompetisiApiController::class, 'create');
Router::add('POST', '/api/kompetisi/create-lomba', KompetisiApiController::class, 'createLomba');
Router::add('POST', '/api/kompetisi/create-team', KompetisiApiController::class, 'createTeam');

// Lost & Found Routes
Router::add('GET', '/lost_found', LostnFoundController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/lost_found/create', LostnFoundController::class, 'create', [MustLoginMiddleware::class]);
Router::add('GET', '/lost_found/detail/([a-zA-Z0-9]+)', LostnFoundController::class, 'detail', [MustLoginMiddleware::class]);

Router::add('POST', '/lost_found/store', LostnFoundController::class, 'store', [MustLoginMiddleware::class]);
Router::add('GET', '/lost_found/edit/([a-zA-Z0-9]+)', LostnFoundController::class, 'edit', [MustLoginMiddleware::class]);
Router::add('POST', '/lost_found/update/([a-zA-Z0-9]+)', LostnFoundController::class, 'update', [MustLoginMiddleware::class]);
Router::add('DELETE', '/lost_found/delete/([a-zA-Z0-9]+)', LostnFoundController::class, 'delete', [MustLoginMiddleware::class]);
Router::add('POST', '/lost_found/complete/([a-zA-Z0-9]+)', LostnFoundController::class, 'markComplete', [MustLoginMiddleware::class]);

// Event Routes
Router::add('GET', '/event', EventController::class, 'index', [MustLoginMiddleware::class]);
Router::add('GET', '/event/create', EventController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/event/store', EventController::class, 'store', [MustLoginMiddleware::class]);
Router::add('GET', '/event/edit/([a-zA-Z0-9]+)', EventController::class, 'edit', [MustLoginMiddleware::class]);
Router::add('POST', '/event/update/([a-zA-Z0-9]+)', EventController::class, 'update', [MustLoginMiddleware::class]);
Router::add('DELETE', '/event/delete/([a-zA-Z0-9]+)', EventController::class, 'delete', [MustLoginMiddleware::class]);
Router::add('POST', '/event/confirm/([a-zA-Z0-9]+)', EventController::class, 'confirm', [MustLoginMiddleware::class]);

// Forum Routes
Router::add('GET', '/forum', ForumController::class, 'forum', [MustLoginMiddleware::class]);
Router::add('GET', '/forum/chat', ForumController::class, 'chat', [MustLoginMiddleware::class]);
Router::add('GET', '/forum/create', ForumController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/create', ForumController::class, 'create', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/create-group', ForumController::class, 'createGroup', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/edit-group', ForumController::class, 'editGroup', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/send-message', ForumController::class, 'sendMessage', [MustLoginMiddleware::class]);
Router::add('POST', '/forum/delete-message', ForumController::class, 'deleteMessage', [MustLoginMiddleware::class]);

// Team API Routes
Router::add('GET', '/api/teams', TeamApiController::class, 'getAllTeams', []);
Router::add('GET', '/api/teams/([a-zA-Z0-9]+)', TeamApiController::class, 'getTeam', []);
Router::add('POST', '/api/teams', TeamApiController::class, 'createTeam', [MustLoginMiddleware::class]);
Router::add('PUT', '/api/teams/([a-zA-Z0-9]+)', TeamApiController::class, 'updateTeam', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/teams/([a-zA-Z0-9]+)', TeamApiController::class, 'deleteTeam', [MustLoginMiddleware::class]);
Router::add('GET', '/api/teams/([a-zA-Z0-9]+)/members', TeamApiController::class, 'getTeamMembers', []);
Router::add('POST', '/api/teams/([a-zA-Z0-9]+)/members', TeamApiController::class, 'addTeamMember', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/teams/([a-zA-Z0-9]+)/members/([a-zA-Z0-9]+)', TeamApiController::class, 'removeTeamMember', [MustLoginMiddleware::class]);
Router::add('PUT', '/api/teams/([a-zA-Z0-9]+)/members/([a-zA-Z0-9]+)/role', TeamApiController::class, 'updateTeamMemberRole', [MustLoginMiddleware::class]);
Router::add('GET', '/api/teams/search', TeamApiController::class, 'searchTeams', []);
Router::add('GET', '/api/teams/user/([a-zA-Z0-9]+)', TeamApiController::class, 'getUserTeams', []);
Router::add('GET', '/api/teams/competition/([a-zA-Z0-9]+)', TeamApiController::class, 'getTeamsByCompetition', []);

Router::add('GET', '/api/events', EventApiController::class, 'getAllEvents', []);
Router::add('GET', '/api/events/([a-zA-Z0-9]+)', EventApiController::class, 'getEvent', []);
Router::add('POST', '/api/events/create', EventApiController::class, 'createEvent');
Router::add('PUT', '/api/events/([a-zA-Z0-9]+)', EventApiController::class, 'updateEvent', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/events/([a-zA-Z0-9]+)', EventApiController::class, 'deleteEvent', [MustLoginMiddleware::class]);
Router::add('POST', '/api/events/([a-zA-Z0-9]+)/confirm', EventApiController::class, 'confirmEvent', [MustLoginMiddleware::class]);
Router::add('POST', '/api/events/([a-zA-Z0-9]+)/register', EventApiController::class, 'registerForEvent', [MustLoginMiddleware::class]);
Router::add('GET', '/api/events/([a-zA-Z0-9]+)/registrations', EventApiController::class, 'getEventRegistrations', []);

Router::add('GET', '/api/lostfound', LostFoundApiController::class, 'getAllItems', []);
Router::add('GET', '/api/lostfound/([a-zA-Z0-9]+)', LostFoundApiController::class, 'getItem', []);
Router::add('POST', '/api/lostfound', LostFoundApiController::class, 'createItem', [MustLoginMiddleware::class]);
Router::add('PUT', '/api/lostfound/([a-zA-Z0-9]+)', LostFoundApiController::class, 'updateItem', [MustLoginMiddleware::class]);
Router::add('DELETE', '/api/lostfound/([a-zA-Z0-9]+)', LostFoundApiController::class, 'deleteItem', [MustLoginMiddleware::class]);
Router::add('POST', '/api/lostfound/([a-zA-Z0-9]+)/complete', LostFoundApiController::class, 'markComplete', [MustLoginMiddleware::class]);

Router::run();