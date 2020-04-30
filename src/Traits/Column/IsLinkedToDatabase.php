<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Table;

trait IsLinkedToDatabase
{
    protected string $dbTable;

    protected ?string $dbField;

    public function getDbTable(): string
    {
        return $this->dbTable;
    }

    public function getDbField(): ?string
    {
        return $this->dbField;
    }

    protected function initializeDatabaseLink(Table $table, ?string $dbField): void
    {
        $this->dbTable = $table->getModel()->getTable();
        $this->dbField = $dbField;
    }
}
