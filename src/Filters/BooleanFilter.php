<?php

namespace Okipa\LaravelTable\Filters;

use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class BooleanFilter extends AbstractFilter
{
    public function __construct(public string $label, public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'filter_boolean_' . $this->attribute;
    }

    protected function label(): string
    {
        return $this->label;
    }

    protected function multiple(): bool
    {
        return false;
    }

    protected function options(): array
    {
        return [
            true => __('Yes'),
            false => __('No'),
        ];
    }

    public function filter(Builder $query, mixed $selected): void
    {
        $query->where($this->attribute, $selected);
    }
}
