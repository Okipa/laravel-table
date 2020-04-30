<?php

namespace Okipa\LaravelTable\Traits\Column;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Column;

trait IsSearchable
{
    protected ?string $dbSearchedTable;

    protected array $dbSearchedFields;

    abstract public function getRequest(): Request;

    abstract public function getSortableColumns(): Collection;

    /**
     * Make the table column searchable.
     * The first param allows you to precise the searched database table (can references a database table alias).
     * The second param allows you to precise the searched database attributes (if not precised, the table database
     * column is searched).
     *
     * @param string $dbSearchedTable
     * @param array $dbSearchedFields
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function searchable(string $dbSearchedTable = null, array $dbSearchedFields = []): Column
    {
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->addToSearchableColumns($this);
        $this->dbSearchedTable = $dbSearchedTable;
        $this->dbSearchedFields = $dbSearchedFields;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getDbSearchedTable(): ?string
    {
        return $this->dbSearchedTable;
    }

    public function getDbSearchedFields(): array
    {
        return $this->dbSearchedFields;
    }
}
