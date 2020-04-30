<?php

namespace Okipa\LaravelTable;

use Closure;
use Okipa\LaravelTable\Traits\Result\HasClasses;
use Okipa\LaravelTable\Traits\Result\HasTitle;

class Result
{
    use HasClasses;
    use HasTitle;

    public function __construct()
    {
        $this->classes = config('laravel-table.classes.results');
    }


}
