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


    /**
     * Route constructor.
     *
     * @param string $controlPath
     * @param array $routes
     * @param array $error
     */
    public function __construct(string $controlPath, array $routes, array $error)
    {
        $this->error = $error;
        $this->routes = $routes;
        $this->controlPath = $controlPath;
        $this->setRoutes();
    }

    /**
     * Configura as rotas
     *
     * @param array $routes
     * @return void
     */
    private function setRoutes()
    {
        $newRoutes = [];

        foreach ($this->routes as $route) {
            $explode = explode('@', $route[1]);

            array_push($newRoutes, [$route[0], $explode[0], $explode[1]]);
        }

        $this->routes = $newRoutes;
    }

    /**
     * Execulta a controler e o método referente a url
     *
     * @return void
     */
    public function run(): void
    {
        $found = false;
        $url = '/' . filter_input(INPUT_GET, 'url', FILTER_SANITIZE_SPECIAL_CHARS);
        $urlArr = $this->urlArray($url);

        $setParam = function ($routeArr, &$route) use ($urlArr) {
            foreach ($routeArr as $k => $v) {
                if ((strpos($routeArr[$k], "{") !== false) && (count($urlArr) == count($routeArr))) {
                    $routeArr[$k] = $urlArr[$k];
                    $this->param  = $urlArr[$k];
                }

                $route = implode('/', $routeArr);
            }
        };

        foreach ($this->routes as $route) {
            $routeArray = explode('/', $route[0]);
            $setParam($routeArray, $route[0]);

            if ($url == $route[0]) {
                $found = true;
                $this->method     = $route[2];
                $this->controller = $route[1];
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

    /**
     * Separa a url em partes
     *
     * @param string $url
     * @return array
     */
    private function urlArray(string $url): array
    {
        $urlArray = explode('/', $url);
        $urlArray = array_merge($urlArray);
        $urlArray[0] = '';
        return $urlArray;
    }
}
