<?php

namespace Okipa\LaravelTable\Test;

use Faker\Factory;
use Okipa\LaravelTable\LaravelTableServiceProvider;
use Okipa\LaravelTable\Test\Fakers\CompaniesFaker;
use Okipa\LaravelTable\Test\Fakers\RoutesFaker;
use Okipa\LaravelTable\Test\Fakers\UsersFaker;
use Orchestra\Testbench\TestCase;

abstract class LaravelTableTestCase extends TestCase
{
    /** @var \Faker\Factory $faker */
    protected $faker;

    use RoutesFaker, UsersFaker, CompaniesFaker;

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [LaravelTableServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->faker = Factory::create();
    }
}
