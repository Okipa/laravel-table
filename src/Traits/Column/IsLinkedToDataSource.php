<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Table;

trait IsLinkedToDataSource
{
    protected string|null $dbTable = null;

    protected string|null $dataSourceField = null;

    public function getDbTable(): string|null
    {
        return $this->dbTable;
    }

    public function getDataSourceField(): string|null
    {
        return $this->dataSourceField;
    }

    protected function initializeDataSourceLink(Table $table, string|null $dataSourceField): void
    {
        $this->dataSourceField = $dataSourceField;
        if ($table->hasDataSource('model')) {
            $this->dbTable = $table->getModel()->getTable();
        }
    }
}
