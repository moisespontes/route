<?php

namespace DevPontes\Route;

/**
 * Description of Route
 *
 * @author Moises Pontes
 * @package DevPontes\Route
 */
class Route
{
    /** @var array */
    private $error;

    /** @var string */
    private $param;

    /** @var array */
    private $routes;

    /** @var string */
    private $method;

    /** @var string */
    private $controller;

    /** @var string */
    private $controlPath;

    /** @var array */
    private $url;

    /**
     * Route constructor.
     *
     * @param string $controlPath
     * @param array $routes
     * @param array $error
     */
    public function __construct(string $controlPath, array $routes, array $error)
    {
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS);

        $this->error = $error;
        $this->controlPath = $controlPath;

        $this->setUrl($url);
        $this->setRoutes($routes);
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
     * Execulta a controler e o método referente a url
     *
     * @return void
     */
    public function run(): void
    {
        $found = false;
        $url = implode('/', $this->url);

        foreach ($this->routes as $route) {
            $routeArray = explode('/', $route['url']);
            $this->setParam($routeArray, $route['url']);

            if ($route['url'] == $url) {
                $found = true;
                $this->method = $route['method'];
                $this->controller = $route['controller'];
                break;
            }
        }

        if ($found) {
            $controller = "{$this->controlPath}\\{$this->controller}";

            call_user_func([new $controller(), $this->method], $this->param);
        } else {
            $controller = "{$this->controlPath}\\{$this->error[0]}";
            call_user_func([new $controller(), $this->error[1]]);
        }
    }
}
