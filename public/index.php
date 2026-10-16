<?php

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
use Illuminate\Contracts\Http\Kernel;
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

<<<<<<< HEAD
// Determine if the application is in maintenance mode...
=======
// Check If The Application Is Under Maintenance

>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

<<<<<<< HEAD
// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
=======
// Register The Auto Loader

require __DIR__.'/../vendor/autoload.php';

// Run The Application

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
