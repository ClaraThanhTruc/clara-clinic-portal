<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AppointmentController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_name('CLARA_CLINIC_SESSID');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

check_session_timeout();
check_session_context();

$router = new Router();

// Khai báo các đường dẫn
$router->get('/', [HomeController::class, 'index']);
$router->get('/appointments', [AppointmentController::class, 'index']);
$router->get('/appointments/create', [AppointmentController::class, 'create']);
$router->post('/appointments', [AppointmentController::class, 'store']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'handleLogin']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/session-demo', [DashboardController::class, 'sessionDemo']);

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));