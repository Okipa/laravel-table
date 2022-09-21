<?php

namespace Okipa\LaravelTable;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Okipa\LaravelTable\Console\Commands\MakeBulkAction;
use Okipa\LaravelTable\Console\Commands\MakeColumnAction;
use Okipa\LaravelTable\Console\Commands\MakeFilter;
use Okipa\LaravelTable\Console\Commands\MakeFormatter;
use Okipa\LaravelTable\Console\Commands\MakeHeadAction;
use Okipa\LaravelTable\Console\Commands\MakeRowAction;
use Okipa\LaravelTable\Console\Commands\MakeTable;

class LaravelTableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-table');
        $this->publishes([
            __DIR__ . '/../config/laravel-table.php' => config_path('laravel-table.php'),
        ], 'laravel-table:config');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-table'),
        ], 'laravel-table:views');
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('table', \Okipa\LaravelTable\Livewire\Table::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-table.php', 'laravel-table');
        $this->commands([
            MakeTable::class,
            MakeFilter::class,
            MakeHeadAction::class,
            MakeBulkAction::class,
            MakeRowAction::class,
            MakeColumnAction::class,
            MakeFormatter::class,
        ]);
    }
}
