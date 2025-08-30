<?php

namespace DevPontes\Route;

use DevPontes\Route\Exception\ErrorRoute;

/**
 * Description of Router
 *
 * @author Moises Pontes
 * @package DevPontes\Route
 */
class Router
{
    /** @var string */
    private $url;

    /** @var string */
    private $method;

    /** @var string */
    private $controller;

    /** @var string */
    private $param;

    /**
     * Router constructor.
     *
     * @param string $url
     * @param string $method
     * @param string $controller
     */
    public function __construct(string $url, string $method, string $controller)
    {
        $this->url = $url;
        $this->method = $method;
        $this->controller = $controller;
    }

    /**
     * @param string|null $param
     * @return void
     */
    public function setParam(?string $param): void
    {
        $this->param = $param;
    }

    /**
     * Retorna a url completa(string) ou em partes(array)
     *
     * @param boolean $part
     * @return string|array
     */
    public function getUrl(bool $part = false): string|array
    {
        if ($part) {
            return explode('/', $this->url);
        }

        return $this->url;
    }

    /**
     * Run the controller
     *
     * @param string $namespace
     * @return void
     */
    public function execute(string $namespace): void
    {
        $controller = $namespace . "\\" . $this->controller;

        if (!class_exists($controller)) {
            throw new ErrorRoute("Class {$controller} not found", 501);
        }

        if (!method_exists($controller, $this->method)) {
            throw new ErrorRoute("Method {$this->method} not found", 405);
        }

        call_user_func([new $controller(), $this->method], $this->param);
    }
}
