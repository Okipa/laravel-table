<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

class Result
{
    protected string $title;

    protected Closure $valueClosure;

    protected string $value;

    public function __construct()
    {
        //
    }

    public static function make(): self
    {
        return new static();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
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
