<?php

namespace Okipa\LaravelTable;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use Okipa\LaravelTable\Exceptions\InvalidColumnSortDirection;

class Column
{
    protected string|null $title = null;

    protected bool $sortable = false;

    protected Closure|null $sortableClosure = null;

    protected bool $sortByDefault = false;

    protected string $sortDirByDefault = 'asc';

    protected bool $searchable = false;

    protected Closure|null $searchableClosure = null;

    protected Closure|AbstractFormatter|null $formatter = null;

    protected Closure|null $columnActionClosure = null;

    protected bool $escapeHtml = false;

    public function __construct(protected string $attribute)
    {
        $this->title = __('validation.attributes.' . $this->attribute);
    }

    public static function make(string $attribute = null): self
    {
        return new self($attribute);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string|null
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

    public function isSortable(Column|null $orderColumn): bool
    {
        if ($orderColumn) {
            return $this->getAttribute() === $orderColumn->getAttribute();
        }

        return $this->sortable;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
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

    public function action(Closure $columnActionClosure): self
    {
        $this->columnActionClosure = $columnActionClosure;

        return $this;
    }

    public function getAction(): Closure|null
    {
        return $this->columnActionClosure;
    }

    public function getValue(Model $model, array $tableColumnActionsArray): HtmlString|string|null
    {
        $columnActionArray = AbstractColumnAction::retrieve(
            $tableColumnActionsArray,
            $model->getKey(),
            $this->getAttribute()
        );
        if ($columnActionArray) {
            $columnActionInstance = AbstractColumnAction::make($columnActionArray);

            return $columnActionInstance->isAllowed()
                ? new HtmlString(AbstractColumnAction::make($columnActionArray)
                    ->render($model, $this->attribute)
                    ->render())
                : null;
        }
        if ($this->formatter instanceof Closure) {
            return $this->manageHtmlEscaping(($this->formatter)($model));
        }
        if ($this->formatter instanceof AbstractFormatter) {
            return $this->manageHtmlEscaping($this->formatter->format($model, $this->attribute));
        }

        return $this->manageHtmlEscaping($model->{$this->attribute});
    }

    protected function manageHtmlEscaping(mixed $value): HtmlString|string
    {
        return $this->escapeHtml ? $value : new HtmlString($value);
    }
}
