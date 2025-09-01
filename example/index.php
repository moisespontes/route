<?php

use DevPontes\Route\Route;

require "../vendor/autoload.php";

$routes = [
    ['/', 'Home@index'],
    ['/blog', 'Blog@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{id}', 'Contact@index'],
    ['/not-found/404', 'Notfound@index'],
];

// External server, ex: Apache -> $_GET['url'];
$url = $_SERVER['REQUEST_URI'];

$route = new Route($routes);

$route->setUrl($url);
$route->namespace('App\Controller');
$route->run();

if ($route->fail()) {
    header('Location: /not-found/404');
}
