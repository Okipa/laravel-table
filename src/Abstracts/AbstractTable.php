<?php

namespace Okipa\LaravelTable\Abstracts;

use Okipa\LaravelTable\Table;

abstract class AbstractTable
{
    /**
     * @return \Okipa\LaravelTable\Table
     * @throws \ErrorException
     */
    public function setup(): Table
    {
        $table = $this->table();
        $this->columns($table);
        $this->resultLines($table);
        $table->configure();

        return $table;
    }

    /**
     * Configure the table itself.
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \ErrorException
     */
    abstract protected function table(): Table;

    /**
     * Configure the table columns.
     *
     * @param \Okipa\LaravelTable\Table $table
     *
     * @throws \ErrorException
     */
    abstract protected function columns(Table $table): void;

    /**
     * Configure the table result lines.
     *
     * @param \Okipa\LaravelTable\Table $table
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function resultLines(Table $table): void
    {
        //
    }
}
