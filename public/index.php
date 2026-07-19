<?php

declare(strict_types=1);

/**
 * Front controller
 *
 * Requires PHP 8.3+
 *
 * Desarrolla JARS Costa Rica
 * https://www.jarscr.com
 */

use App\Config;
use Core\Error;
use Core\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

Config::load();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
ini_set('display_errors', Config::showErrors() ? '1' : '0');
ini_set('display_startup_errors', Config::showErrors() ? '1' : '0');
ini_set('log_errors', '1');

set_error_handler([Error::class, 'errorHandler']);
set_exception_handler([Error::class, 'exceptionHandler']);

/**
 * Security headers (when not already set by the web server)
 */
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; style-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; font-src 'self' https://cdn.jsdelivr.net; frame-ancestors 'self'");
}

/**
 * Routing
 */
$router = new Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');

$queryString = $_SERVER['QUERY_STRING'] ?? '';
$router->dispatch($queryString);
