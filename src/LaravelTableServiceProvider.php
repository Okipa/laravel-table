<?php

namespace Okipa\LaravelTable;

use Illuminate\Support\ServiceProvider;
use Okipa\LaravelHtmlHelper\HtmlHelperServiceProvider;
use Okipa\LaravelTable\Console\Commands\MakeTable;

class LaravelTableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'laravel-table');
        $this->publishes([
            __DIR__ . '/../config/laravel-table.php' => config_path('laravel-table.php'),
        ], 'laravel-table:config');
        $this->publishes([
            __DIR__ . '/../lang' => resource_path('lang/vendor/laravel-table'),
        ], 'laravel-table:translations');
        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/laravel-table'),
        ], 'laravel-table:views');
        // we load the laravel html helper package
        // https://github.com/Okipa/laravel-html-helper
        $this->app->register(HtmlHelperServiceProvider::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-table.php', 'laravel-table');
        $this->commands([MakeTable::class]);
    }
}
