<?php

namespace Okipa\LaravelTable\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class BooleanFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string|null
    {
        $boolean = $model->{$attribute};
        if (! isset($boolean)) {
            return null;
        }

        return $boolean
            ? '<span class="text-success">' . config('laravel-table.icon.active') . '</span>'
            : '<span class="text-danger">' . config('laravel-table.icon.inactive') . '</span>';
    }
}
