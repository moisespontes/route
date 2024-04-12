# Route by @Devpontes

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
"devpontes/route": "1.0.*"
```

or run

```bash
composer require devpontes/route
```

## Documentation

##### To use the route it is necessary to redirect all the application's requests to the index.php file where the traffic will be treated, see the example

1. Para usar o route é necessário redirecionar todas as requisições da aplicação para o arquivo index.php onde o tráfego será tratado, veja o exemplo.

#### Apache

```apacheconfig
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

##### It will be necessary to configure an array with all the application's routes

2. Será necessário configurar um array com todas as rotas da aplicação.

```php
<?php

/** Define routes */
$routes = [
    ['/','Home@index'],
    ['/contact', 'Contact@index'],
    ['/about', 'About@index'],
    ['/blog/{artigo}', 'Blog@index'],
];
```

##### At initialization inform the path of the controllers that was configured in **_composer.json_**, the array of configured routes and an array with a controller and a method to handle routes that are not configured

3. Na inicialização informe o caminho das controllers que foi configurado no **_composer.json_**, o array de rotas configuradas e um array com uma controller e um método para tratar rotas que não estão configuradas.

#### Usage

```php
$controlPath  = "App\Controller";
$controlError = ['NotFound', 'index'];

$route = new \DevPontes\Route\Route($controlPath, $routes, $controlError);
$route->run();
```

## Credits

- [Moises Pontes](https://github.com/moisespontes) (Developer)

## License

The MIT License (MIT).
