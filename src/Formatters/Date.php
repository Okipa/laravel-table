<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Date extends AbstractFormatter
{
    public function __construct(protected string $format, protected string $timezone)
    {
        //
    }

    public function format(Model $model, string $key): string
    {
        return $this->timezone
            ? $model->{$key}->timezone($this->timezone)->format($this->format)
            : $model->{$key}->format($this->format);
    }
}
