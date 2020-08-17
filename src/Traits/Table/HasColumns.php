<?php

namespace Okipa\LaravelTable\Traits\Table;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Column;

trait HasColumns
{
    protected Collection $columns;

    protected Collection $sortableColumns;

    protected Collection $searchableColumns;

    /**
     * @param string|null $dbField
     *
     * @return \Okipa\LaravelTable\Column
     * @throws \ErrorException
     */
    public function column(?string $dbField = null): Column
    {
        /** @var \Okipa\LaravelTable\Table $this */
        $this->checkModelIsDefined();
        /** @var \Okipa\LaravelTable\Table $this */
        $column = new Column($this, $dbField);
        /** @var $this $this */
        $this->columns->push($column);

        return $column;
    }

    public function getColumnsCount(): int
    {
        $extraColumnsCount = $this->isRouteDefined('show')
        || $this->isRouteDefined('edit')
        || $this->isRouteDefined('destroy') ? 1 : 0;

        return $this->getColumns()->count() + $extraColumnsCount;
    }

    abstract public function isRouteDefined(string $routeKey): bool;

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addToSearchableColumns(Column $column): void
    {
        $this->searchableColumns->push($column);
    }

    public function getSearchableTitles(): string
    {
        return $this->getSearchableColumns()->map(fn(Column $column) => $column->getTitle())->implode(', ');
    }

    public function getSearchableColumns(): Collection
    {
        return $this->searchableColumns;
    }

    public function addToSortableColumns(Column $column): void
    {
        $this->sortableColumns->push($column);
    }

    public function getSortableColumns(): Collection
    {
        return $this->sortableColumns;
    }

    protected function initializeColumns(): void
    {
        $this->columns = new Collection();
        $this->sortableColumns = new Collection();
        $this->searchableColumns = new Collection();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @throws \ErrorException
     */
    protected function checkColumnsValidity(Builder $query): void
    {
        $this->getColumns()->map(function (Column $column) use ($query) {
            $this->checkSortableColumnHasAttribute($column);
            $isSearchable = in_array(
                $column->getDbField(),
                $this->getSearchableColumns()->map(fn(Column $column) => $column->getDbField())->toArray()
            );
            if ($isSearchable) {
                $this->checkSearchableColumnHasAttribute($column);
                $this->checkSearchedAttributeDoesExistInRelatedTable($column, $query);
            }
        });
    }

    /**
     * @param \Okipa\LaravelTable\Column $column
     *
     * @throws \ErrorException
     */
    protected function checkSortableColumnHasAttribute(Column $column): void
    {
        if (! $column->getDbField() && $column->getIsSortable()) {
            $errorMessage = 'One of the sortable table columns has no defined database column. You have to define a '
                . 'database column for each sortable table columns by setting a string parameter in the '
                . '« column » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * @param \Okipa\LaravelTable\Column $column
     *
     * @throws \ErrorException
     */
    protected function checkSearchableColumnHasAttribute(Column $column): void
    {
        if (! $column->getDbField()) {
            $errorMessage = 'One of the searchable table columns has no defined database column. You have to define '
                . 'a database column for each searchable table columns by setting a string parameter in '
                . 'the « column » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * @param \Okipa\LaravelTable\Column $column
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @throws \ErrorException
     */
    protected function checkSearchedAttributeDoesExistInRelatedTable(Column $column, Builder $query): void
    {
        $searchedDatabaseColumns = $column->getDbSearchedFields() ?: [$column->getDbField()];
        $tableDbData = $this->getColumnDbInfo($column, $query);
        foreach ($searchedDatabaseColumns as $searchedDatabaseColumn) {
            if (! in_array($searchedDatabaseColumn, $tableDbData['columns'])) {
                $tableAlias = Arr::get($tableDbData, 'alias');
                $dynamicMessagePart = $tableAlias
                    ? '« ' . $tableDbData['table'] . ' » (aliased as « ' . $tableAlias . ' ») table'
                    : '« ' . $tableDbData['table'] . ' » table';
                $errorMessage = 'The table column with related « ' . $searchedDatabaseColumn . ' » database column is '
                    . 'searchable and does not exist in the ' . $dynamicMessagePart
                    . '. Set the database searched table and (optionally) columns with the « sortable » '
                    . 'method arguments.';
                throw new ErrorException($errorMessage);
            }
        }
    }

    protected function getColumnDbInfo(Column $column, Builder $query): array
    {
        $dbTable = $column->getDbSearchedTable() ?: $column->getDbTable();
        if ($column->getDbSearchedTable()) {
            $fromSqlStatement = last(explode(' from ', (string) $query->toSql()));
            $dbAliases = [];
            preg_match_all('/["`]([a-zA-Z0-9_]*)["`] as ["`]([a-zA-Z0-9_]*)["`]/', $fromSqlStatement, $dbAliases);
            if (! empty(array_filter($dbAliases))) {
                $position = array_keys(Arr::where(
                    array_shift($dbAliases),
                    fn($alias) => Str::contains($alias, $dbTable)
                ));
                $dbAlias = head($dbAliases)[head($position)];
                $dbColumns = Schema::getColumnListing($dbAlias);

                return ['table' => $dbTable, 'alias' => $dbAlias, 'columns' => $dbColumns];
            }
        }
        $dbAlias = null;
        $dbColumns = Schema::getColumnListing($dbTable);

        return ['table' => $dbTable, 'alias' => $dbAlias, 'columns' => $dbColumns];
    }

    /** @throws \ErrorException */
    protected function checkIfAtLeastOneColumnIsDeclared(): void
    {
        if ($this->getColumns()->isEmpty()) {
            $errorMessage = 'No column has been added to the table. Please add at least one column by using the '
                . '« column() » method on the table object.';
            throw new ErrorException($errorMessage);
        }
    }
}
