<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait HasCollection
{
    protected Collection|null $collection = null;

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableDataSourceAlreadyDefined
     */
    public function fromCollection(Collection $collection): Table
    {
        $this->checkDataSourceHasNotAlreadyBeenDefined();
        $this->setDataSource('collection');
        $this->collection = $collection;

        return $this;
    }

    public function getCollection(): Collection|null
    {
        return $this->collection;
    }
}
