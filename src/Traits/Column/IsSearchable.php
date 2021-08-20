<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait IsSearchable
{
    protected string|null $dbSearchedTable;

    protected array $dbSearchedFields;

    public function searchable(string $dbSearchedTable = null, array $dbSearchedFields = []): Column
    {
        $this->dbSearchedTable = $dbSearchedTable;
        $this->dbSearchedFields = $dbSearchedFields;
        $this->getTable()->addToSearchableColumns($this);

        return $this;
    }

    public function getDbSearchedTable(): string|null
    {
        return $this->dbSearchedTable;
    }

    public function getDbSearchedFields(): array
    {
        return $this->dbSearchedFields;
    }
}
