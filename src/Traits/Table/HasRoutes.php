<?php

namespace Okipa\LaravelTable\Traits\Table;

use ErrorException;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Okipa\LaravelTable\Table;

trait HasRoutes
{
    protected array $routes = [];

    /** @throws \ErrorException */
    public function routes(array $routes): Table
    {
        $this->checkRoutesValidity($routes);
        $this->routes = $routes;

        return $this;
    }

    /** @throws \ErrorException */
    protected function checkRoutesValidity(array $routes): void
    {
        $requiredRouteKeys = ['index'];
        $optionalRouteKeys = ['create', 'edit', 'destroy', 'show'];
        $allowedRouteKeys = array_merge($requiredRouteKeys, $optionalRouteKeys);
        $this->checkRequiredRoutesValidity($routes, $requiredRouteKeys);
        $this->checkAllowedRoutesValidity($routes, $allowedRouteKeys);
        $this->checkRoutesStructureValidity($routes);
    }

    /** @throws \ErrorException */
    protected function checkRequiredRoutesValidity(array $routes, array $requiredRouteKeys): void
    {
        $routeKeys = array_keys($routes);
        foreach ($requiredRouteKeys as $requiredRouteKey) {
            if (! in_array($requiredRouteKey, $routeKeys, true)) {
                throw new ErrorException(
                    'The required "' . $requiredRouteKey
                    . '" route key is missing. Use the "routes()" method to declare it.'
                );
            }
        }
    }

    /** @throws \ErrorException */
    protected function checkAllowedRoutesValidity(array $routes, array $allowedRouteKeys): void
    {
        foreach (array_keys($routes) as $routeKey) {
            if (! in_array($routeKey, $allowedRouteKeys, true)) {
                throw new ErrorException(
                    'The "' . $routeKey . '" key is not an authorized route key (' . implode(', ', $allowedRouteKeys)
                    . '). Fix your routes declaration in the "routes()" method.'
                );
            }
        }
    }

    /** @throws \ErrorException */
    protected function checkRoutesStructureValidity(array $routes): void
    {
        $requiredRouteParams = ['name'];
        foreach ($routes as $routeKey => $route) {
            foreach ($requiredRouteParams as $requiredRouteParam) {
                if (! array_key_exists($requiredRouteParam, $route)) {
                    throw new ErrorException(
                        'The "' . $requiredRouteParam . '" key is missing from the "' . $routeKey
                        . '" route definition. Fix your routes declaration in the "routes()" method.'
                    );
                }
            }
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoute(string $routeKey, array $params = []): string
    {
        $this->checkRouteIsDefined($routeKey);

        return route(
            $this->routes[$routeKey]['name'],
            array_merge($params, Arr::get($this->routes[$routeKey], 'params', []))
        );
    }

    protected function checkRouteIsDefined(string $routeKey): void
    {
        if (! $this->isRouteDefined($routeKey)) {
            throw new InvalidArgumentException(
                'Invalid "$routeKey" argument for the "route()" method. The route key "'
                . $routeKey . '" has not been found in the routes stack.'
            );
        }
    }

    public function isRouteDefined(string $routeKey): bool
    {
        return ! empty($this->routes[$routeKey]);
    }
}
