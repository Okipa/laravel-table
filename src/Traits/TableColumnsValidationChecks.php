<?php

namespace Okipa\LaravelTable\Traits;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Column;

trait TableColumnsValidationChecks
{
    /**
     * Check if a route is defined from its key.
     *
     * @param string $routeKey
     *
     * @return bool
     */
    abstract public function isRouteDefined(string $routeKey): bool;

    /**
     * Check column model is defined.
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkModelIsDefined(): void
    {
        if (! $this->model instanceof Model) {
            $errorMessage = 'The table model has not been defined or is not an instance of « '
                            . Model::class . ' ».';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * Check the columns validity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function checkColumnsValidity(Builder $query): void
    {
        $this->columns->map(function (Column $column) use ($query) {
            $this->checkSortableColumnHasAttribute($column);
            $isSearchable = in_array(
                $column->databaseDefaultColumn,
                $this->searchableColumns->pluck('databaseDefaultColumn')->toArray()
            );
            if ($isSearchable) {
                $this->checkSearchableColumnHasAttribute($column);
                $this->checkSearchedAttributeDoesExistInRelatedTable($column, $query);
            }
        });
    }

    /**
     * Check if the sortable table column has an database column defined.
     *
     * @param \Okipa\LaravelTable\Column $column
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSortableColumnHasAttribute(Column $column): void
    {
        if (! $column->databaseDefaultColumn && $column->isSortable) {
            $errorMessage = 'One of the sortable table columns has no defined database column. You have to define a '
                            . 'database column for each sortable table columns by setting a string parameter in the '
                            . '« column() » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * Check if the searchable table column has a defined database column.
     *
     * @param \Okipa\LaravelTable\Column $column
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSearchableColumnHasAttribute(Column $column): void
    {
        if (! $column->databaseDefaultColumn) {
            $errorMessage = 'One of the searchable table columns has no defined database column. You have to define '
                            . 'a database column for each searchable table columns by setting a string parameter in '
                            . 'the « column() » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * Check that the given database column does exist in the (aliased or not) column database table.
     *
     * @param \Okipa\LaravelTable\Column $column
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSearchedAttributeDoesExistInRelatedTable(Column $column, Builder $query): void
    {
        $searchedDatabaseColumns = $column->databaseSearchedColumns ?: [$column->databaseDefaultColumn];
        $tableData = $this->tableData($column, $query);
        foreach ($searchedDatabaseColumns as $searchedDatabaseColumn) {
            if (! in_array($searchedDatabaseColumn, $tableData['columns'])) {
                $tableAlias = Arr::get($tableData, 'alias');
                $dynamicMessagePart = $tableAlias
                    ? '« ' . $tableData['table'] . ' » (aliased as « ' . $tableAlias . ' ») table'
                    : '« ' . $tableData['table'] . ' » table';
                $errorMessage = 'The table column with related « ' . $searchedDatabaseColumn . ' » database column is '
                                . 'searchable and does not exist in the ' . $dynamicMessagePart
                                . '. Set the database searched table and (optionally) columns with the « sortable() » '
                                . 'method arguments.';
                throw new ErrorException($errorMessage);
            }
        }
    }

    /**
     * Get data (alias, columns) from database table.
     *
     * @param \Okipa\LaravelTable\Column $column
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return array
     */
    protected function tableData(Column $column, Builder $query): array
    {
        $table = $column->databaseSearchedTable ?: $column->databaseDefaultTable;
        if ($column->databaseSearchedTable) {
            $fromSqlStatement = last(explode(' from ', (string) $query->toSql()));
            $aliases = [];
            preg_match_all('/["`]([a-zA-Z0-9_]*)["`] as ["`]([a-zA-Z0-9_]*)["`]/', $fromSqlStatement, $aliases);
            if (! empty(array_filter($aliases))) {
                $position = array_keys(Arr::where(array_shift($aliases), function ($alias) use ($table) {
                    return Str::contains($alias, $table);
                }));
                $alias = head($aliases)[head($position)];
                $columns = Schema::getColumnListing($alias);

                return compact('table', 'columns', 'alias');
            }
        }
        $alias = null;
        $columns = Schema::getColumnListing($table);

        return compact('table', 'alias', 'columns');
    }

    /**
     * Check if at least one column is declared.
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkIfAtLeastOneColumnIsDeclared(): void
    {
        if ($this->columns->isEmpty()) {
            $errorMessage = 'No column has been added to the table. Please add at least one column by using the '
                            . '« column() » method on the table object.';
            throw new ErrorException($errorMessage);
        }
    }
}
