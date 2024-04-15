<?php

use DevPontes\Route\Route;

require "../vendor/autoload.php";

$routes = [
    //Rotas
    ['/','Home@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{param}', 'Contact@index'],
];

$route = new Route($routes);
$route->namespace('App\Controller');
$route->run();

if ($route->fail()) {
    header('Location: /not-found');
}
