<?php
use System\Core\Kernel;
use System\Core\Request;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../system/Support/helpers.php';

$kernel = new Kernel();

$request  = new Request();
$response = $kernel->handle($request);

$kernel->terminate($response);
