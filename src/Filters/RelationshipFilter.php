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
        public array $selectOptions,
        public bool $multipleChoice = true
    ) {
        //
    }

    protected function identifier(): string
    {
        return 'relationship_' . $this->relationship;
    }

    protected function class(): string|null
    {
        return null;
    }

    protected function label(): string
    {
        return $this->label;
    }

    protected function multiple(): bool
    {
        return $this->multipleChoice;
    }

    protected function options(): array
    {
        return $this->selectOptions;
    }

    public function filter(Builder $query, mixed $selected): void
    {
        $query->whereHas($this->relationship, fn(Builder $category) => $category->whereIn('id', Arr::wrap($selected)));
    }
}
