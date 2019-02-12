<?php

namespace Okipa\LaravelTable\Test\Fakers;

trait RoutesFaker
{
    public function routes(array $entities = ['users'], array $routes = ['index'])
    {
        foreach ($entities as $model) {
            foreach ($routes as $route) {
                app('router')->get('/' . $model . '/' . $route, [
                    'as' => $model . '.' . $route, function () use ($model, $route) {
                        return $model . '.' . $route . ' route.';
                    },
                ]);
            }
        }
    }
}
