<?php
// ------------------------------------------------------
// Front Controller (public/index.php)
// Entry point for all HTTP requests
// ------------------------------------------------------

use System\Core\Kernel;
use System\Http\Request;

// Define important paths
define('ROOTPATH', dirname(__DIR__) . '/');
define('APPPATH', ROOTPATH . 'app/');
define('SYSTEMPATH', ROOTPATH . 'system/');
define('WRITEPATH', ROOTPATH . 'storage/');

// Autoload dependencies
require_once ROOTPATH . 'vendor/autoload.php';

// Load global helper functions
require_once SYSTEMPATH . 'Support/helpers.php';

// Bootstrap the Kernel
$kernel = new Kernel();

// Capture the Request
$request = new Request();

// Handle the Request and get Response
$response = $kernel->handle($request);

// Send the Response to the browser
$kernel->terminate($response);
