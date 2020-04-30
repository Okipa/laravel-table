<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Table;

trait HasTable
{
    protected Table $table;

    public function getTable(): Table
    {
        return $this->table;
    }

    protected function initializeTable(Table $table): void
    {
        $this->table = $table;
    }
}
