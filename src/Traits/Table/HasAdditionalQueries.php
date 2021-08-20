<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Table;

trait HasAdditionalQueries
{
    protected Closure|null $additionalQueriesClosure = null;

    public function query(Closure $additionalQueriesClosure): Table
    {
        $this->additionalQueriesClosure = $additionalQueriesClosure;

        return $this;
    }

    protected function executeAdditionalQueries(Builder $query): void
    {
        $closure = $this->getAdditionalQueriesClosure();
        if ($closure) {
            $closure($query);
        }
    }

    public function getAdditionalQueriesClosure(): Closure|null
    {
        return $this->additionalQueriesClosure;
    }
}
