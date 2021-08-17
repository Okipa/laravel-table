<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Collection;
use Okipa\LaravelTable\Exceptions\TableCollectionNotFound;
use Okipa\LaravelTable\Table;

trait HasCollection
{
    protected Collection|null $collection = null;

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableBuildModeAlreadyDefined
     */
    public function collection(Collection $collection): Table
    {
        $this->checkNoBuildModeIsAlreadyDefined();
        $this->setBuildMode('collection');
        $this->collection = $collection;

        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\TableCollectionNotFound */
    protected function checkCollectionIsDefined(): void
    {
        if ($this->buildModeId === $this->getBuildModeFromKey('collection')['id'] && ! $this->getCollection()) {
            throw new TableCollectionNotFound('The table is in "collection" build mode but none has been defined.');
        }
    }

    public function getCollection(): ?Collection
    {
        return $this->collection;
    }
}
