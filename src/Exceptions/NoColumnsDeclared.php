<?php

namespace Okipa\LaravelTable\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class NoColumnsDeclared extends Exception
{
    public function __construct(Model $model)
    {
        parent::__construct('No columns are declared for ' . $model::class . ' table.');
    }
}
