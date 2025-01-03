<?php
// Set the session timeout to 4 hours (14400 seconds)
//ini_set('session.gc_maxlifetime', 14400);
//ini_set('session.cookie_lifetime', 14400);
session_start([
    'cookie_lifetime' => 14400,   // 1 hour
    'cookie_secure'   => true,   // Should be true on HTTPS environments
    'cookie_httponly' => true,   // HttpOnly
    'cookie_samesite' => 'Strict'
]);
//$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/src/Autoloader.php';

use App\Autoloader;

$autoloader = new Autoloader();
$autoloader->register();

// Map namespaces to directories
$autoloader->addNamespace('App\\Controllers', __DIR__ . '/src/Controllers');
$autoloader->addNamespace('App\\Models', __DIR__ . '/src/Models');
$autoloader->addNamespace('App', __DIR__ . '/src');
// App\Utils
$autoloader->addNamespace('App\\Utils', __DIR__ . '/src/Utils');
