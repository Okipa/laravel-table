<?php

namespace Okipa\LaravelTable\Facades;

use Illuminate\Support\Facades\Facade;

class Table extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Table';
    }
}
