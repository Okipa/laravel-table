<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Result
{
    protected string $title;

    protected Closure $formatClosure;

    protected string $value;

    protected bool $escapeHtml = false;

    public function __construct()
    {
        //
    }

    public static function make(): self
    {
        return new self();
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function format(Closure $formatClosure, bool $escapeHtml = false): self
    {
        $this->formatClosure = $formatClosure;
        $this->escapeHtml = $escapeHtml;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function compute(Builder $totalRowsQuery, Collection $displayedRows): self
    {
        $this->value = ($this->formatClosure)($totalRowsQuery, $displayedRows);

        return $this;
    }

    public function getValue(): string
    {
        return $this->manageHtmlEscaping($this->value);
    }

    protected function manageHtmlEscaping(mixed $value): HtmlString|string
    {
        return $this->escapeHtml ? $value : new HtmlString($value);
    }
}
