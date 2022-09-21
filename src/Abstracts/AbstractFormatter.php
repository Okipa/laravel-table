<?php

namespace Okipa\LaravelTable\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractFormatter
{
    abstract public function format(Model $model, string $attribute): mixed;
}
