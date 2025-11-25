<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Define the base path for the application, so that we can use it in the future (e.g. for deployment)
$BASE_PATH = __DIR__.'/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Define the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Check if environment file is missing
if (!file_exists($BASE_PATH.'/.env') && strpos($requestUri, 'pre-setup') === false) {
    header('Location: /pre-setup.html');
    exit;
}

// Register the Composer autoloader...
require $BASE_PATH.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $BASE_PATH.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
