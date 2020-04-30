<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HasSorting
{
    protected string $sortByField = 'sort_by';

    protected ?string $sortByValue = null;

    protected string $sortDirField = 'sort_dir';

    protected ?string $sortDirValue = null;

    abstract public function getRequest(): Request;

    abstract public function getSortableColumns(): Collection;

    public function defineSortByValue(string $sortByValue): void
    {
        $this->sortByValue = $sortByValue;
    }

    public function definedSortDirValue(string $sortDirValue): void
    {
        $this->sortDirValue = $sortDirValue;
    }

    protected function applySortingOnQuery(Builder $query): void
    {
        $this->sortByValue = $this->getProcessedSortByValue();
        $this->sortDirValue = $this->getProcessedSortDirValue();
        if ($this->getSortByValue() && $this->getSortDirValue()) {
            $query->orderBy($this->getSortByValue(), $this->getSortDirValue());
        }
    }

    protected function getProcessedSortByValue(): ?string
    {
        $requestSortByField = $this->getRequest()->get($this->getSortByField());
        if ($requestSortByField) {
            return $requestSortByField;
        }
        if ($this->getSortByValue()) {
            return $this->getSortByValue();
        }

        return $this->getSortableColumns()->isNotEmpty()
            ? $this->getSortableColumns()->first()->getDbField()
            : null;
    }

    protected function getProcessedSortDirValue(): string
    {
        $requestSortDirField = $this->getRequest()->get($this->getSortDirField());
        if ($requestSortDirField) {
            return $requestSortDirField;
        }
        if ($this->getSortDirValue()) {
            return $this->getSortDirValue();
        }

        return 'asc';
    }

    protected function reDefineSortByField(string $sortByField): void
    {
        $this->sortByField = $sortByField;
    }

    public function getSortByField(): string
    {
        return $this->sortByField;
    }

    public function getSortByValue(): ?string
    {
        return $this->sortByValue;
    }

    protected function reDefineSortDirField(string $sortDirField): void
    {
        $this->sortDirField = $sortDirField;
    }

    public function getSortDirField(): string
    {
        return $this->sortDirField;
    }

    public function getSortDirValue(): ?string
    {
        return $this->sortDirValue;
    }
}
