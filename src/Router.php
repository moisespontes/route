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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Run the controller
     *
     * @return void
     */
    public function execute(string $namespace, mixed $param): void
    {
        $controller = $namespace . "\\" . $this->controller;

        if (!class_exists($controller)) {
            throw new ErrorRoute("Class {$controller} not found", 501);
        }

        if (!method_exists($controller, $this->method)) {
            throw new ErrorRoute("Method {$this->method} not found", 405);
        }

        call_user_func([new $controller(), $this->method], $param);
    }
}
