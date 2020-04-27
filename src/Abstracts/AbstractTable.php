<?php

namespace Okipa\LaravelTable\Abstracts;

use Okipa\LaravelTable\Table;

abstract class AbstractTable
{
    public function __construct()
    {
        $table = $this->table();
        $this->columns($table);
        $this->resultLines($table);
    }

    abstract protected function table(): Table;

    abstract protected function columns(Table $table): void;

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    protected function resultLines(Table $table): void
    {
        //
    }
}
