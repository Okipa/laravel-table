<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class StrLimit extends AbstractFormatter
{
    public function __construct(protected int|null $limit = null)
    {
        //
    }

    public function format(Model $model, string $key): string
    {
        return Str::limit($model->{$key}, $this->limit);
    }
}
