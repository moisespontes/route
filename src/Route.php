<?php

namespace DevPontes\Route;

use DevPontes\Route\Traits\Matcher;
use DevPontes\Route\Exception\ErrorRoute;

/**
 * Description of Route
 *
 * @author Moises Pontes
 * @package DevPontes\Route
 */
class Route
{
    use Matcher;

    /** @var ErrorRoute */
    private $fail;

    /** @var string */
    private $namespace;

    /** @var array */
    private $url;

    /** @var array */
    private $routes = [];

    /**
     * Route constructor.
     *
     * @param array $routes
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
        foreach ($routes as $route) {
            $action = explode('@', $route[1]);
            $url = preg_replace('/\{[^}]+\}/', '{}', $route[0]);
            $this->routes[] = new Router($url, $action[1], $action[0]);
        }
    }

    /**
     * Run route
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $router = $this->match($this->url);

            if (empty($router)) {
                throw new ErrorRoute("Page not found", 404);
            } else {
                $router->execute($this->namespace);
            }
        } catch (ErrorRoute $err) {
            $this->fail = $err;
        }
    }
}
