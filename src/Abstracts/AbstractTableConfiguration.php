<?php

namespace Okipa\LaravelTable\Abstracts;

use Okipa\LaravelTable\Table;

abstract class AbstractTableConfiguration
{
    public function setup(Table $table): Table
    {
        $this->table($table);
        $this->columns($table);
        $this->resultLines($table);

        return $table;
    }

    abstract protected function table(Table $table): void;

    abstract protected function columns(Table $table): void;

    protected function resultLines(Table $table): void
    {
        //
    }
}
