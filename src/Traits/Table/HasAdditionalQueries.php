<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Table;

trait HasAdditionalQueries
{
    protected ?Closure $additionalQueriesClosure = null;

    /**
     * Set the query closure that will be executed during the table generation.
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Builder $query.
     *
     * @param \Closure $additionalQueriesClosure
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function query(Closure $additionalQueriesClosure): Table
    {
        $this->additionalQueriesClosure = $additionalQueriesClosure;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function executeAdditionalQueries(Builder $query): void
    {
        $closure = $this->getAdditionalQueriesClosure();
        if ($closure) {
            $closure($query);
        }
    }

    public function getAdditionalQueriesClosure(): ?Closure
    {
        return $this->additionalQueriesClosure;
    }
}
