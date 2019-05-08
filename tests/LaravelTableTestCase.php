<?php

namespace Okipa\LaravelTable\Test;

use Faker\Factory;
use Okipa\LaravelTable\Test\Fakers\CompaniesFaker;
use Okipa\LaravelTable\Test\Fakers\RoutesFaker;
use Okipa\LaravelTable\Test\Fakers\UsersFaker;
use Orchestra\Testbench\TestCase;

abstract class LaravelTableTestCase extends TestCase
{
    protected $faker;
    use RoutesFaker;
    use UsersFaker;
    use CompaniesFaker;

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Okipa\LaravelTable\LaravelTableServiceProvider',
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/database/migrations'),
        ]);
        $this->faker = Factory::create();
    }
}
