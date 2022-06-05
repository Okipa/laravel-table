<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class Result
{
    protected Closure $valueClosure;

    protected string $value;

    public function __construct(protected string $title)
    {
        $this->title = __($title);
    }

    public static function make(string $title): self
    {
        return new static($title);
    }

    public function value(Closure $valueClosure): self
    {
        $this->valueClosure = $valueClosure;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function compute(Builder $totalRowsQuery, Collection $displayedRows): self
    {
        $this->value = ($this->valueClosure)($totalRowsQuery, $displayedRows);

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
