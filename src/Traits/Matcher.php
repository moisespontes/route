<?php

namespace DevPontes\Route\Traits;

use DevPontes\Route\Router;

trait Matcher
{
    /**
     * Set param
     *
     * @param string $routeUrl
     * @return array
     */
    protected function setParam(string $routeUrl): array
    {
        $param = null;
        $urlParts = explode('/', $routeUrl);

        foreach ($urlParts as $key => $part) {
            if (preg_match('/^\{.*\}$/', $part) && count($this->url) == count($urlParts)) {
                $urlParts[$key] = $this->url[$key];
                $param = $this->url[$key];
            }
        }

        return [implode('/', $urlParts), $param];
    }

    /**
     * Find the corresponding route based on the given URL
     *
     * @param array $url
     * @return Router|null
     */
    protected function match(array $url): ?Router
    {
        $url = implode('/', $url);

        /** @var Router $route */
        foreach ($this->routes as $route) {
            [$routeurl, $param] = $this->setParam($route->getUrl());

            if ($routeurl == $url) {
                $route->setParam($param);
                return $route;
            }
        }

        return null;
    }
}
