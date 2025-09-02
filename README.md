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

###### Route is a simple and lightweight route management component designed for small projects based on MVC architecture.

O Route é um componente simples e leve de gerenciamento de rotas, projetado para pequenos projetos baseados em arquitetura MVC.

### Highlights

- Suporte a rotas nomeadas. (named routes)
- Modo estrito para diferenciação de URLs. (strict mode)
- Configuração simples e intuitiva. (simple to set up)
- Padrão de rota: controller/method/{parameter}.

## Instalação

Installation is available through **Composer**:

```bash
"devpontes/route": "3.*"
```

or run

```bash
composer require devpontes/route
```

## Documentation

### 1. Configuração

###### To use Route, all application requests must be redirected to the _index.php_ file, which will act as the **_Front Controller_**. Example using _Apache (.htaccess)_

Para utilizar o Route, todas as requisições da aplicação devem ser redirecionadas para o arquivo _index.php_, que atuará como o **_Front Controller_**. Exemplo usando o _Apache (.htaccess)_:

#### Apache

```apacheconfig
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

### 2. Definição de Rotas

###### Routes must be defined in an **array** in the format _[path, controller@method]_

As rotas devem ser definidas em um **array** no formato _[path, controller@method]_:

```php
$routes = [
    ['/', 'Home@index'],
    ['/blog', 'Blog@index'],
    ['/contato', 'Contact@index'],
    ['/contato/{id}', 'Contact@index'],
    ['/not-found/404', 'Notfound@index'],
];
```

### 3. Captura da URL

###### The request URL must be captured and passed to the component in the constructor or via the setter method.

A URL da requisição deve ser capturada e passada ao componente no construtor ou via metodo setter. Exemplo com servidor _Apache_:

```php
$url = $_GET['url'];
```

ou usando a pasta publica

```php
$url = $_SERVER['REQUEST_URI'];
```

### 4. Inicialização

###### On component initialization: 1 - Enter the routes array; 2 - Configure the controllers' namespace (the same as defined in _composer.json_); 3 - Run with the _run()_ method.

Na inicialização do componente:

1. Informe o array de rotas.
2. Configure o **_namespace_** dos controladores (mesmo definido no _composer.json_).
3. Execute com o método _run()_.

##### Usage

```php
$route = new \DevPontes\Route\Route($routes);
$route->setUrl($url);
$route->namespace("App\Controller");
$route->run();
```

### 5. Tratamento de Erros

###### To capture unconfigured routes, use the _fail_ method

Para capturar rotas não configuradas, utilize o método _fail_:

```php
if ($route->fail()) {
    header('Location: /not-found/404');
}
```

### 6. Modo Estrito

###### Strict mode differentiates between routes with and without trailing slashes.

O modo estrito diferencia rotas com e sem barra final. Exemplo:

- _/sobre_ ≠ _/sobre/_ (strict mode enabled).
- _/sobre_ = _/sobre/_ (strict mode disabled).

##### Enabling/disabling

```php
$route->setStrictMode(true);
```

## Credits

- [Moises Pontes](https://github.com/moisespontes) (Developer)

## License

The MIT License (MIT).
