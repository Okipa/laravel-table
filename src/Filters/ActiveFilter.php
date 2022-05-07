<?php

namespace Okipa\LaravelTable\Filters;

use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class ActiveFilter extends AbstractFilter
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'active';
    }

    protected function class(): string|null
    {
        return null;
    }

    protected function label(): string
    {
        return __('Active');
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

    protected function filter(Builder $query, mixed $value): void
    {
        $query->where($this->attribute, $value);
    }
}
