<?php
define('ROOT_PATH', dirname(__DIR__));

// Autoload core classes
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/Helpers.php';
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

// Auto-login via "Remember Me" cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $parts = explode('|', $_COOKIE['remember_user']);
    if (count($parts) === 2) {
        $userId = $parts[0];
        $hash   = $parts[1];
        $expectedHash = hash_hmac('sha256', $userId, 'SobatBogorRememberMeSaltKey');
        if (hash_equals($expectedHash, $hash)) {
            require_once ROOT_PATH . '/app/models/User.php';
            $userModel = new User();
            $user = $userModel->findById((int)$userId);
            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role']      = $user['role'];
            } else {
                setcookie('remember_user', '', time() - 3600, '/');
            }
        } else {
            setcookie('remember_user', '', time() - 3600, '/');
        }
    }
}

// Initialize dan dispatch router
$router = new Router();
require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();
