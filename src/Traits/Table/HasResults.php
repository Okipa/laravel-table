<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Collection;
use Okipa\LaravelTable\Result;

trait HasResults
{
    protected Collection $results;

    protected function initializeResults(): void
    {
        $this->results = new Collection();
    }

    public function result(): Result
    {
        $result = new Result();
        $this->getResults()->push($result);

        return $result;
    }

    public function getResults(): Collection
    {
        return $this->results;
    }
}
