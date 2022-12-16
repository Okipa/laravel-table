<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Testing\Constraints\SeeInOrder;
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

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LaravelTableServiceProvider::class,
        ];
    }

    protected function assertSeeHtmlInOrder(string $html, array $values): void
    {
        self::assertTrue((new SeeInOrder($html))->matches($values));
    }
}
