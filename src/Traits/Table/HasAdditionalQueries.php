<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Table;

trait HasAdditionalQueries
{
    protected ?Closure $additionalQueriesClosure = null;

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
