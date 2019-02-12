<?php

namespace Okipa\LaravelTable\Traits;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Okipa\LaravelTable\Column;

trait ColumnsValidationChecks
{
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
                $column->attribute,
                $this->searchableColumns->pluck('attribute')->toArray()
            );
            if ($isSearchable) {
                $this->checkSearchableColumnHasAttribute($column);
                $this->checkSearchedAttributeDoesExistInRelatedTable($column, $query);
            }
        });
    }

    /**
     * Check if the sortable column has an attribute.
     *
     * @param \Okipa\LaravelTable\Column $column
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSortableColumnHasAttribute(Column $column): void
    {
        if (! $column->attribute && $column->isSortable) {
            $errorMessage = 'One of the sortable columns has no defined attribute. You have to define a column '
                            . 'attribute for each sortable columns by setting a string parameter in the '
                            . '« column() » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * Check if the searchable column has an attribute.
     *
     * @param \Okipa\LaravelTable\Column $column
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSearchableColumnHasAttribute(Column $column): void
    {
        if (! $column->attribute) {
            $errorMessage = 'One of the searchable columns has no defined attribute. You have to define a column '
                            . 'attribute for each searchable columns by setting a string parameter in the '
                            . '« column() » method.';
            throw new ErrorException($errorMessage);
        }
    }

    /**
     * Check that the given attribute or alias exist in the column related table.
     *
     * @param \Okipa\LaravelTable\Column            $column
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkSearchedAttributeDoesExistInRelatedTable(Column $column, Builder $query): void
    {
        $attributes = $column->searchedDatabaseColumns ? $column->searchedDatabaseColumns : [$column->attribute];
        $searchedDatabaseTable = $column->searchedDatabaseTable
            ? $column->searchedDatabaseTable
            : $column->databaseDefaultTable;
        $fromSqlStatement = last(explode('from', $query->toSql()));
        preg_match_all(
            '/["`]([a-zA-Z0-9_]*)["`] as ["`]([a-zA-Z0-9_]*)["`]/',
            $fromSqlStatement,
            $aliases
        );
        if (! empty(array_filter($aliases))) {
            $position = array_keys(array_where(
                array_shift($aliases),
                function ($alias) use ($searchedDatabaseTable) {
                    return str_contains($alias, $searchedDatabaseTable);
                }
            ));
            $aliasedTable = head($aliases)[head($position)];
            $searchedDatabaseTableColumns = Schema::getColumnListing($aliasedTable);
        } else {
            $searchedDatabaseTableColumns = Schema::getColumnListing($searchedDatabaseTable);
        }
        foreach ($attributes as $attribute) {
            if (! in_array($attribute, $searchedDatabaseTableColumns)) {
                $dynamicMessagePart = isset($aliasedTable)
                    ? '« ' . $aliasedTable . ' » (aliased as « ' . $searchedDatabaseTable . ' ») table'
                    : '« ' . $searchedDatabaseTable . ' » table';
                $errorMessage = 'The given attribute « ' . $attribute . ' » has not been found in the searchable-'
                                . 'column ' . $dynamicMessagePart . '. Set the searched table and attributes '
                                . 'with the « sortable() » method.';
                throw new ErrorException($errorMessage);
            }
        }
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

    /**
     * Check if a route is defined from its key.
     *
     * @param string $routeKey
     *
     * @return bool
     */
    abstract public function isRouteDefined(string $routeKey): bool;
}
