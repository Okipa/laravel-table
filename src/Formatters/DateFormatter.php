<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class DateFormatter extends AbstractFormatter
{
    public function __construct(protected string $format, protected string|null $timezone = null)
    {
        //
    }

    public function format(Model $model, string $attribute): string|null
    {
        $date = $model->{$attribute};
        if (! $date) {
            return null;
        }

        return $this->timezone
            ? $date->timezone($this->timezone)->format($this->format)
            : $date->format($this->format);
    }
}
