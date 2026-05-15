<?php

// use Illuminate\Foundation\Application;
// use Illuminate\Http\Request;

// define('LARAVEL_START', microtime(true));

// // Determine if the application is in maintenance mode...
// if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
//     require $maintenance;
// }

// file_put_contents('/var/www/html/storage/logs/timing.log', date('H:i:s') . ' before autoload: ' . microtime(true) . "\n", FILE_APPEND);

// // Register the Composer autoloader...
// require __DIR__ . '/../vendor/autoload.php';

// file_put_contents('/var/www/html/storage/logs/timing.log', date('H:i:s') . ' after autoload: ' . microtime(true) . "\n", FILE_APPEND);

// // Bootstrap Laravel and handle the request...
// /** @var Application $app */
// $app = require_once __DIR__ . '/../bootstrap/app.php';

// file_put_contents('/var/www/html/storage/logs/timing.log', date('H:i:s') . ' before kernel handle: ' . microtime(true) . "\n", FILE_APPEND);

// $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// $response = $kernel->handle(
//     $request = Illuminate\Http\Request::capture()
// );

// file_put_contents('/var/www/html/storage/logs/timing.log', date('H:i:s') . ' after kernel handle: ' . microtime(true) . "\n", FILE_APPEND);

// $response->send();

// file_put_contents('/var/www/html/storage/logs/timing.log', date('H:i:s') . ' after send: ' . microtime(true) . "\n", FILE_APPEND);

// $app->handleRequest(Request::capture());

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());
