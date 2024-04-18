<?php

namespace DevPontes\Route\Traits;

use DevPontes\Route\Router;

trait Matcher
{
    /**
     * Set param
     *
     * @param string $routeUrl
     * @return string
     */
    protected function setParam(string $routeUrl): string
    {
        $routeUrl = explode('/', $routeUrl);

        foreach ($routeUrl as $k => $v) {
            if (preg_match('/^\{.*\}$/', $v) && (count($this->url) == count($routeUrl))) {
                $routeUrl[$k] = $this->url[$k];
                $this->param  = $this->url[$k];
            }

            $route = implode('/', $routeUrl);
        }

        return $route;
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

        foreach ($this->routes as $route) {
            $routeurl = $this->setParam($route->getUrl());

            if ($routeurl == $url) {
                return $route;
            }
        }

        return null;
    }
}
