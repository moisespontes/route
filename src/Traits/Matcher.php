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
    protected function setParam(string $routeUrl, &$param): string
    {
        $url = explode('/', $routeUrl);

        foreach ($url as $k => $v) {
            if (preg_match('/^\{.*\}$/', $v) && (count($this->url) == count($url))) {
                $url[$k] = $this->url[$k];
                $param  = $this->url[$k];
            }

            $routeUrl = implode('/', $url);
        }

        return $routeUrl;
    }

    /**
     * Find the corresponding route based on the given URL
     *
     * @param array $url
     * @return Router|null
     */
    protected function match(array $url): ?Router
    {
        $param = null;
        $url = implode('/', $url);

        /** @var Router $route */
        foreach ($this->routes as $route) {
            $routeurl = $this->setParam($route->getUrl(), $param);

            if ($routeurl == $url) {
                $route->setParam($param);
                return $route;
            }
        }

        return null;
    }
}
