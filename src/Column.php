<?php

namespace Okipa\LaravelTable;

class Column
{
    protected string|null $title = null;

    protected bool $sortable = false;

    protected bool $sortedByDefault = false;

    protected bool $sortedAscByDefault = false;

    protected bool $searchable = false;

    public function __construct(protected string $key)
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

    public function sortable(bool $sortByDefault = false, bool $sortAscByDefault = true): self
    {
        $this->sortable = true;
        $this->sortedByDefault = $sortByDefault;
        $this->sortedAscByDefault = $sortAscByDefault;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
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
}
