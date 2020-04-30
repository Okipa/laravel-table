<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;

trait HasIdentifier
{
    protected ?string $identifier = null;

    public function identifier(string $identifier): Table
    {
        $this->identifier = Str::slug($identifier);
        $this->redefineInteractionFieldsFromIdentifier();

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function redefineInteractionFieldsFromIdentifier(): void
    {
        $underscoredIdentifier = $this->getIdentifier() ? str_replace('-', '_', $this->getIdentifier()) . '_' : '';
        $this->reDefineRowsNumberField($underscoredIdentifier . $this->getRowsNumberField());
        $this->reDefineSearchField($underscoredIdentifier . $this->getSearchField());
        $this->reDefineSortByField($underscoredIdentifier . $this->getSortByField());
        $this->reDefineSortDirField($underscoredIdentifier . $this->getSortDirField());
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    abstract protected function reDefineRowsNumberField(string $rowsNumberField): void;

    abstract public function getRowsNumberField(): string;

    abstract protected function reDefineSearchField(string $searchField): void;

    abstract public function getSearchField(): string;

    abstract protected function reDefineSortByField(string $sortByField): void;

    abstract public function getSortByField(): string;

    abstract protected function reDefineSortDirField(string $sortDirField): void;

    abstract public function getSortDirField(): string;
}
