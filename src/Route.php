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

    /** @var boolean */
    private $strictMode = false;

    /**
     * Route constructor.
     *
     * @param array $routes List of registered routes
     * @param string $url Request URL
     */
    public function __construct(array $routes, string $url = '')
    {
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
     * Enable or disable strict mode for URL normalization.
     *
     * - When strict mode is enabled, trailing slashes are preserved.
     * - When disabled, trailing slashes are removed (except for root "/").
     *
     * @param boolean $strictMode
     * @return self
     */
    public function setStrictMode(bool $strictMode): self
    {
        $this->strictMode = $strictMode;
        return $this;
    }

    /**
     * Set the URL
     *
     * @param string $url Request URL
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = explode('/', $this->normalizeUrl($url));

        return $this;
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
     *  Normalize a URL string:
     *
     * - Trim spaces and collapse multiple slashes into one
     * - Remove trailing slash (except root "/") when strictMode = false
     * - Ensure the URL always starts with "/"
     *
     * @param string $url
     * @return string
     */
    private function normalizeUrl(string $url): string
    {
        $url = preg_replace('#/+#', '/', trim($url));

        if ($url === '' || $url === false) {
            return '/';
        }

        if (!$this->strictMode && $url !== '/') {
            $url = rtrim($url, '/');
        }

        return $url[0] === '/' ? $url : '/' . $url;
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
            if (!isset($route[0], $route[1])) {
                throw new ErrorRoute("Route must be defined as ['/url', 'Controller@method']");
            }

            if (!str_contains($route[1], '@')) {
                throw new ErrorRoute("Invalid route configuration: ({$route[0]}) => {$route[1]}");
            }

            [$controller, $method] = explode('@', $route[1]);
            $url = preg_replace('/\{[^}]+\}/', '{}', $route[0]);
            $this->routes[] = new Router($url, $method, $controller);
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
