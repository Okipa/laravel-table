<?php

namespace Okipa\LaravelTable\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Livewire\LivewireServiceProvider;
use Okipa\LaravelTable\LaravelTableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->setUiConfigDynamically();
    }

    protected function setUiConfigDynamically(): void
    {
        $testNamespace = $this::class;
        if (Str::contains($testNamespace, 'Bootstrap4')) {
            Config::set('laravel-table.ui', 'bootstrap-4');

            return;
        }
        Config::set('laravel-table.ui', 'bootstrap-5');
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use in-memory sqlite.
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelTableServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
