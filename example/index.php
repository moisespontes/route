<?php

use DevPontes\Route\Route;

require "../vendor/autoload.php";

$routes = [
    //Rotas
    ['/','Home@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{param}', 'Contact@index'],
];

$route = new Route('App\Controller', $routes, ['NotFound', 'index']);
$route->run();
