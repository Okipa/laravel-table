<?php

namespace Okipa\LaravelTable\Facades;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Okipa\LaravelTable\Testing\Manager actingAs(Authenticatable $user, string|null $driver = null)
 * @method static \Okipa\LaravelTable\Testing\Assert test(string $config, array $configParams = [])
 *
 * @see \Okipa\LaravelTable\Testing\Manager
 * @see \Okipa\LaravelTable\Testing\Assert
 */
class TestableTable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel_table_testable';
    }
}
