<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Column
{
    protected string|null $title = null;

    protected bool $sortable = false;

    protected Closure|null $sortableClosure = null;

    protected bool $sortedByDefault = false;

    protected bool $sortedAscByDefault = true;

    protected bool $searchable = false;

    protected Closure|AbstractFormatter|null $formatter = null;

    protected bool $escapeHtml = false;

    public function __construct(protected string|null $key = null)
    {
        //
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title ?: __('validation.attributes.' . $this->key);
    }

    public function sortable(Closure $sortableClosure = null): self
    {
        $this->sortable = true;
        $this->sortableClosure = $sortableClosure;

        return $this;
    }

    public function sortByDefault(bool $sortAscByDefault = true): self
    {
        $this->sortedByDefault = true;
        $this->sortedAscByDefault = $sortAscByDefault;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function getSortableClosure(): Closure|null
    {
        return $this->sortableClosure;
    }

    public function isSortedByDefault(): bool
    {
        return $this->sortedByDefault;
    }

    public function isSortedAscByDefault(): bool
    {
        return $this->sortedAscByDefault;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function format(Closure|AbstractFormatter $formatter, bool $escapeHtml = false): void
    {
        $this->formatter = $formatter;
        $this->escapeHtml = $escapeHtml;
    }

    public function getValue(Model $row): HtmlString|string|null
    {
        if ($this->formatter instanceof Closure) {
            return $this->manageHtmlEscaping(($this->formatter)($row));
        }
        if ($this->formatter instanceof AbstractFormatter) {
            return $this->manageHtmlEscaping($this->formatter->format($row));
        }

        return $this->key ? $this->manageHtmlEscaping(data_get($row, $this->key)) : null;
    }

    protected function manageHtmlEscaping(mixed $value): HtmlString|string
    {
        return $this->escapeHtml ? $value : new HtmlString($value);
    }
}
