<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait IsSearchable
{
    protected ?string $dbSearchedTable;

    protected array $dbSearchedFields;

    public function searchable(string $dbSearchedTable = null, array $dbSearchedFields = []): Column
    {
        $this->dbSearchedTable = $dbSearchedTable;
        $this->dbSearchedFields = $dbSearchedFields;
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->addToSearchableColumns($this);

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
