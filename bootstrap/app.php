<?php

use System\Http\Request;
use System\Http\Response;
use System\Http\Router;
use System\Support\Env;
use System\Support\Config;
use System\Database\DB;

require_once __DIR__ . '/../system/Support/Helpers.php';

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');
Config::load(__DIR__ . '/../config');

$dbConfig = Config::get('database');
DB::connect($dbConfig);

$request  = new Request();
$response = new Response();
$router   = new Router($request, $response);

require __DIR__ . '/../config/routes.php';
require __DIR__ . '/../routes/web.php';


