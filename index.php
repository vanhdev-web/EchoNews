<?php

use App\Core\Router;

// Start session
session_start();

// Configuration
define('BASE_PATH', __DIR__);

// Helper functions (load trước để có current_domain())
require_once BASE_PATH . '/helpers.php';

// CURRENT_DOMAIN is now dynamically determined in helpers.php
// No need to define it here anymore
define('DB_HOST', 'localhost');
define('DB_NAME', 'news-project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DISPLAY_ERROR', true);

// Mail config
define('MAIL_HOST', 'sandbox.smtp.mailtrap.io');
define('SMTP_AUTH', true);
define('MAIL_USERNAME', 'your_mailtrap_username');
define('MAIL_PASSWORD', 'your_mailtrap_password');
define('MAIL_PORT', 2525);

// Error reporting
if (DISPLAY_ERROR) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Autoloader
require_once BASE_PATH . '/app/Core/Autoloader.php';

try {
    // Load routes and dispatch
    $router = require_once BASE_PATH . '/routes.php';
    $router->dispatch();
    
} catch (Exception $e) {
    if (DISPLAY_ERROR) {
        echo "<h1>Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "An error occurred. Please try again later.";
    }
}