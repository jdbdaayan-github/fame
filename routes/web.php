<?php
$router->get('/', 'HomeController@index');
$router->get('/hello/{name}', 'HomeController@sayHello');
