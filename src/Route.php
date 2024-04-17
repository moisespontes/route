<?php

namespace DevPontes\Route;

use DevPontes\Route\Exception\ErrorRoute;

/**
 * Description of Route
 *
 * @author Moises Pontes
 * @package DevPontes\Route
 */
class Route
{
    /** @var ErrorRoute */
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
     * @return ErrorRoute|null
     */
    public function fail(): ?ErrorRoute
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
     * Separate the URL
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
     * Config the routes
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
     * Run the controller
     *
     * @return void
     */
    private function execute(): void
    {
        if (empty($this->controller)) {
            throw new ErrorRoute("Page not found", 404);
        }

        $controller = $this->namespace . "\\" . $this->controller;

        if (!class_exists($controller)) {
            throw new ErrorRoute("Class {$controller} not found", 501);
        }

        if (!method_exists($controller, $this->method)) {
            throw new ErrorRoute("Method {$this->method} not found", 405);
        }

        call_user_func([new $controller(), $this->method], $this->param);
    }

    /**
     * Run route
     *
     * @return void
     */
    public function run(): void
    {
        try {
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

            $this->execute();
        } catch (ErrorRoute $err) {
            $this->fail = $err;
        }
    }
}
