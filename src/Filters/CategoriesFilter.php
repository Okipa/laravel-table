<?php

namespace Okipa\LaravelTable\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class CategoriesFilter extends AbstractFilter
{
    public function __construct(protected array $categoryOptions, protected Closure $categoriesFilterClosure)
    {
        //
    }

    protected function identifier(): string
    {
        return 'categories';
    }

    protected function class(): string|null
    {
        return null;
    }

    protected function label(): string
    {
        return __('Categories');
    }

    protected function multiple(): bool
    {
        return true;
    }

    protected function options(): array
    {
        return $this->categoryOptions;
    }

    public function filter(Builder $query, mixed $value): void
    {
        ($this->categoriesFilterClosure)($query);
    }
}
