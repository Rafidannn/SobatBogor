<?php
/**
 * public/index.php
 * Application entry point — all requests are routed through here.
 * TODO: Wire up autoloader, config, router in Tugas 1
 */

define('ROOT_PATH', dirname(__DIR__));

// Autoload core classes
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Router.php';

// Load config
$config = require ROOT_PATH . '/config/app.php';

// Initialize and dispatch router
$router = new Router();
require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();
