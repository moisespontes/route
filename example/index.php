<?php

use DevPontes\Route\Route;

require "../vendor/autoload.php";

$routes = [
    //Rotas
    ['/','Home@index'],
    ['/blog', 'Blog@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{id}', 'Contact@index'],
    ['/not-found/404', 'Notfound@index'],
];

$route = new Route($routes);

$route->namespace('App\Controller');
$route->run();

if ($route->fail()) {
    header('Location: /not-found/404');
}
