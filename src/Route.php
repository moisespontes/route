<?php

namespace DevPontes\Route;

use Throwable;

/**
 * Description of Route
 *
 * @author Moises Pontes
 * @package DevPontes\Route
 */
class Route
{
    /** @var Throwable */
    private $fail;

    /** @var string */
    private $param;

    /** @var array */
    private $routes;

    /** @var string */
    private $method;

    /** @var string */
    private $controller;

    /** @var string */
    private $namespace;

    /** @var array */
    private $url;

    /**
     * Route constructor.
     *
     * @param array $routes
     * @param array $error
     */
    public function __construct(array $routes)
    {
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS);

        $this->setUrl($url);
        $this->setRoutes($routes);
    }

    /**
     * @return Throwable|null
     */
    public function fail(): ?Throwable
    {
        return $this->fail;
    }

    /**
     * Set namespace app
     *
     * @param string $namespace
     * @return Route
     */
    public function namespace(string $namespace): Route
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Separa a url em partes
     *
     * @param string|null $url
     * @return void
     */
    private function setUrl(?string $url): void
    {
        $url = "/" . $url;
        $this->url = explode('/', $url);
    }

    /**
     * Configura as rotas
     *
     * @param array $routes
     * @return void
     */
    private function setRoutes(array $routes): void
    {
        $this->routes = [];

        foreach ($routes as $route) {
            $dispatch = explode('@', $route[1]);

            $set = [
                "url"        => $route[0],
                "controller" => $dispatch[0],
                "method"     => $dispatch[1],
            ];

            array_push($this->routes, $set);
        }
    }

    /**
     * @param array $routeArr
     * @param string $route
     * @return void
     */
    private function setParam(array $routeArr, string &$route): void
    {
        foreach ($routeArr as $k => $v) {
            if (preg_match('/^\{.*\}$/', $v) && (count($this->url) == count($routeArr))) {
                $routeArr[$k] = $this->url[$k];
                $this->param  = $this->url[$k];
            }

            $route = implode('/', $routeArr);
        }
    }

    /**
     * Execulta a controller
     *
     * @return void
     */
    public function run(): void
    {
        $url = implode('/', $this->url);

        foreach ($this->routes as $route) {
            $routeArray = explode('/', $route['url']);
            $this->setParam($routeArray, $route['url']);

            if ($route['url'] == $url) {
                $this->method = $route['method'];
                $this->controller = $route['controller'];
                break;
            }
        }

        try {
            $controller = $this->namespace . "\\" . $this->controller;
            call_user_func([new $controller(), $this->method], $this->param);
        } catch (Throwable $th) {
            $this->fail = $th;
        }
    }
}
