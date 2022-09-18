<?php

namespace Okipa\LaravelTable\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class RelationshipFilter extends AbstractFilter
{
    public function __construct(
        public string $label,
        public string $relationship,
        public array $options,
        public bool $multiple = true
    ) {
        //
    }

    protected function identifier(): string
    {
        return 'filter_relationship_' . $this->relationship;
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
        $query->whereHas(
            $this->relationship,
            fn (Builder $category) => $category->whereIn($this->modelKeyName, Arr::wrap($selected))
        );
    }
}
