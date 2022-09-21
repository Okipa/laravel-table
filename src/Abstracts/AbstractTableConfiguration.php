<?php

namespace Okipa\LaravelTable\Abstracts;

use Okipa\LaravelTable\Table;

abstract class AbstractTableConfiguration
{
    public function setup(): Table
    {
        $table = $this->table();
        $table->columns($this->columns());
        $table->results($this->results());

        return $table;
    }

    abstract protected function table(): Table;

    abstract protected function columns(): array;

    protected function results(): array
    {
        return [];
    }
}
