<?php

use DevPontes\Route\Route;

require "../vendor/autoload.php";

$routes = [
    //Rotas
    ['/','Home@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{p}', 'Contact@index'],
    ['/not-found/404', 'Notfound@index'],
];

$route = new Route($routes);

$route->namespace('App\Controller');
$route->run();

if ($route->fail()) {
    header('Location: /not-found/404');
}
