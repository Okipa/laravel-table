<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Collection;
use Okipa\LaravelTable\Exceptions\TableCollectionNotFound;
use Okipa\LaravelTable\Exceptions\TableModeAlreadyDefined;
use Okipa\LaravelTable\Table;

trait HasCollection
{
    protected ?Collection $collection = null;

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableModeAlreadyDefined
     */
    public function collection(Collection $collection): Table
    {
        if ($this->mode) {
            throw new TableModeAlreadyDefined('Table mode has already been set to "' . $this->mode . '".');
        }
        $this->mode = self::COLLECTION_MODE;
        $this->collection = $collection;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\TableCollectionNotFound */
    protected function checkCollectionIsDefined(): void
    {
        if (! $this->getCollection()) {
            throw new TableCollectionNotFound('The table is in collection mode but none has been found.');
        }
    }

    public function getCollection(): ?Collection
    {
        return $this->collection;
    }
}
