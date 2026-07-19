<?php
define('ROOT_PATH', dirname(__DIR__));

// Autoload core classes
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
require_once ROOT_PATH . '/middleware/AdminMiddleware.php';

// Load config & definisikan konstanta global
$config = require ROOT_PATH . '/config/app.php';
define('BASE_URL', $config['url']);
define('APP_NAME', $config['name']);

// Mulai session global
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize dan dispatch router
$router = new Router();
require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();
