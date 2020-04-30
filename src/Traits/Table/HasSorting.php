<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Builder;

trait HasSorting
{
    protected string $sortByField = 'sort_by';

    protected ?string $sortByValue = null;

    protected string $sortDirField = 'sort_dir';

    protected ?string $sortDirValue = null;

    public function getSortByField(): string
    {
        return $this->sortByField;
    }

    public function getSortDirField(): string
    {
        return $this->sortDirField;
    }

    public function getSortByValue(): ?string
    {
        return $this->sortByValue;
    }

    public function defineSortByValue(string $sortByValue): void
    {
        $this->sortByValue = $sortByValue;
    }

    public function getSortDirValue(): ?string
    {
        return $this->sortDirValue;
    }

    public function definedSortDirValue(string $sortDirValue): void
    {
        $this->sortDirValue = $sortDirValue;
    }

    protected function applySortingOnQuery(Builder $query): void
    {
        $this->sortByValue = $this->getRequest()->get($this->getSortByField())
            ?: ($this->getSortByValue() ?: optional($this->getSortableColumns()->first())->getDbField());
        $this->sortDirValue = $this->getRequest()->get($this->getSortDirField())
            ?: ($this->getSortDirValue() ?: 'asc');
        if ($this->getSortByValue() && $this->getSortDirValue()) {
            $query->orderBy($this->getSortByValue(), $this->getSortDirValue());
        }
    }
}
