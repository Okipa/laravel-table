<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Abstracts\AbstractCellAction;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use Okipa\LaravelTable\Exceptions\InvalidColumnSortDirection;

class Column
{
    protected bool $sortable = false;

    protected Closure|null $sortableClosure = null;

    protected bool $sortByDefault = false;

    protected string $sortDirByDefault = 'asc';

    protected bool $searchable = false;

    protected Closure|null $searchableClosure = null;

    protected Closure|AbstractFormatter|null $formatter = null;

    protected Closure|null $cellActionClosure = null;

    protected bool $escapeHtml = false;

    public function __construct(protected string $title, protected string|null $key = null)
    {
        $this->key = $key ?: Str::snake($title);
    }

    public static function make(string $title, string $key = null): self
    {
        return new static($title, $key);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function sortable(Closure $sortableClosure = null): self
    {
        $this->sortable = true;
        $this->sortableClosure = $sortableClosure;

        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidColumnSortDirection */
    public function sortByDefault(string $sortDir = 'asc'): self
    {
        if (! in_array($sortDir, ['asc', 'desc'], true)) {
            throw new InvalidColumnSortDirection($sortDir);
        }
        $this->sortByDefault = true;
        $this->sortDirByDefault = $sortDir;

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
        return $this->sortByDefault;
    }

    public function getSortDirByDefault(): string
    {
        return $this->sortDirByDefault;
    }

    public function searchable(Closure $searchableClosure = null): self
    {
        $this->searchable = true;
        $this->searchableClosure = $searchableClosure;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function getSearchableClosure(): Closure|null
    {
        return $this->searchableClosure;
    }

    public function format(Closure|AbstractFormatter $formatter, bool $escapeHtml = false): self
    {
        $this->formatter = $formatter;
        $this->escapeHtml = $escapeHtml;

        return $this;
    }

    public function cellAction(Closure $cellActionClosure): self
    {
        $this->cellActionClosure = $cellActionClosure;

        return $this;
    }

    public function getCellAction(): Closure|null
    {
        return $this->cellActionClosure;
    }

    public function getValue(Model $model, array $tableCellActionsArray): HtmlString|string|null
    {
        if ($this->formatter instanceof Closure) {
            return $this->manageHtmlEscaping(($this->formatter)($model));
        }
        if ($this->formatter instanceof AbstractFormatter) {
            return $this->manageHtmlEscaping($this->formatter->format($model, $this->key));
        }
        $cellActionArray = AbstractCellAction::retrieve($tableCellActionsArray, $model->getKey(), $this->getKey());
        if ($cellActionArray) {
            return AbstractCellAction::make($cellActionArray)->render($model, $this->key);
        }

        return $this->key ? $this->manageHtmlEscaping(data_get($model, $this->key)) : null;
    }

    protected function manageHtmlEscaping(mixed $value): HtmlString|string
    {
        return $this->escapeHtml ? $value : new HtmlString($value);
    }
}
