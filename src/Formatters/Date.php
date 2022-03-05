<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Date extends AbstractFormatter
{
    public function __construct(protected string $format)
    {
        //
    }

    public function format(Model $model, string $key): string
    {
        return $model->{$key}->format($this->format);
    }
}
