<?php

namespace Okipa\LaravelTable\Filters;

use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Abstracts\AbstractFilter;

class EmailVerifiedFilter extends AbstractFilter
{
    public function __construct(public string $attribute)
    {
        //
    }

    protected function identifier(): string
    {
        return 'email_verified';
    }

    protected function class(): string|null
    {
        return null;
    }

    protected function label(): string
    {
        return __('Email Verified');
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
        $selected ? $query->whereNotNull($this->attribute) : $query->whereNull($this->attribute);
    }
}
