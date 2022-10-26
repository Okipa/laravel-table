<?php

namespace Okipa\LaravelTable\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class ValueFilter extends AbstractFilter
{
    public function __construct(
        public string $label,
        public string $attribute,
        public array $options,
        public bool $multiple = true
    ) {
        //
    }

    protected function identifier(): string
    {
        return 'filter_value_' . $this->attribute;
    }

    protected function label(): string
    {
        return $this->label;
    }

    protected function multiple(): bool
    {
        return $this->multiple;
    }

    protected function options(): array
    {
        return $this->options;
    }

    public function filter(Builder $query, mixed $selected): void
    {
        $query->whereIn($this->attribute, Arr::wrap($selected));
    }
}
