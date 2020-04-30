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
        $this->rowsNumberField = $underscoredIdentifier . $this->getRowsNumberField();
        $this->searchField = $underscoredIdentifier . $this->getSearchField();
        $this->sortByField = $underscoredIdentifier . $this->getSortByField();
        $this->sortDirField = $underscoredIdentifier . $this->getSortDirField();
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }
}
