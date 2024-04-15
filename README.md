# Route by @Devpontes

[![Maintainer](https://img.shields.io/badge/maintainer-@moi.pontes-blue.svg?style=flat-square)](https://instagram.com/moi.pontes)
[![Source Code](https://img.shields.io/badge/source-moisespontes/route-blue.svg?style=flat-square)](https://github.com/moisespontes/route)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/devpontes/route.svg?style=flat-square)](https://packagist.org/packages/devpontes/route)
[![Latest Version](https://img.shields.io/github/release/moisespontes/route.svg?style=flat-square)](https://github.com/moisespontes/route/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/moisespontes/route.svg?style=flat-square)](https://scrutinizer-ci.com/g/moisespontes/route)
[![Quality Score](https://img.shields.io/scrutinizer/g/moisespontes/route.svg?style=flat-square)](https://scrutinizer-ci.com/g/moisespontes/route)
[![Total Downloads](https://img.shields.io/packagist/dt/devpontes/route.svg?style=flat-square)](https://packagist.org/packages/devpontes/route)

## About Route componet

##### Route is a simple route component for small MVC-based projects

Route é um simples componente de rotas para projetos pequenos baseados em MVC.

### Highlights

- Rotas nomeadas. (named routes)
- Verbos GET e POST. (GET and POST verbs)
- Simples de configurar. (simple to set up)
- Padrão controller/método/{paramentro}. (pattern controller/method/{parameter}).

## Installation

Installation is available through Composer:

```bash
"devpontes/route": "2.*"
```

or run

```bash
composer require devpontes/route
```

## Documentation

##### To use the route, it is necessary to redirect all application requests to the index.php file, which will be the **_Front Controller_**, where all application traffic will be handled, see the example

1. Para usar o route é necessário redirecionar todas as requisições da aplicação para o arquivo index.php que será o **_Front Controller_**, onde todo o tráfego da aplicação será tratado, veja o exemplo:

#### Apache

```apacheconfig
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

##### Configure an array with the application routes

2. Configure um array com as rotas da aplicação.

```php
<?php

// Define routes
$routes = [
    ['/','Home@index'],
    ['/about', 'About@index'],
    ['/contact', 'Contact@index'],
    ['/blog/{artigo}', 'Blog@index'],
];
```

##### At initialization, enter the route array. Then, use the **_namespace_** method passing the path that was configured in the **_composer.json_** autoload. Lastly, use **_rum_** to execute

3. Na inicialização, informe o array de rotas. Em seguida, utilize o método **_namespace_** passando o caminho que foi configurado no autoload do **_composer.json_**. Por último, use o **_rum_** para executar.

#### Usage

```php
$namespace = "App\Controller";
$route = new \DevPontes\Route\Route($routes);
$route->namespace($namespace);
$route->run();
```

##### To handle unconfigured routes, use the **_fail_** method, see

- Para tratar rotas não configuradas use o método **_fail_**, veja:

```php
// Redirect
if ($route->fail()) {
    header('Location: /not-found');
}
```

## Credits

- [Moises Pontes](https://github.com/moisespontes) (Developer)

## License

The MIT License (MIT).
