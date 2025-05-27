<?php

require_once __DIR__.'/../vendor/autoload.php';

session_start();
// Отладочный вывод
error_log("Session started: " . session_id());
if (!isset($_SESSION['user'])) {
    error_log("No user in session");
} else {
    error_log("User in session: " . print_r($_SESSION['user'], true));
}

$protectedRoutes = ['/', '/view', '/sections', '/tickets'];
if (in_array(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $protectedRoutes) 
    && empty($_SESSION['user'])) {
    header("Location: /login");
    exit;
}

use Dotenv\Dotenv;
use App\Core\Router;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

(new Router())->dispatch();
