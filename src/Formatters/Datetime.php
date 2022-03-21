<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Datetime extends AbstractFormatter
{
    public function __construct(protected string $format, protected string|null $timezone = null)
    {
        //
    }

    public function format(Model $model, string $attribute): string
    {
        return $this->timezone
            ? $model->{$attribute}->timezone($this->timezone)->format($this->format)
            : $model->{$attribute}->format($this->format);
    }
}
