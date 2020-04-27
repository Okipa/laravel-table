<?php

namespace Okipa\LaravelTable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelTable\Column;

trait TableInteractions
{
    protected string $rowsField = 'rows';

    protected int $rowsValue;

    protected string $sortByField = 'sort_by';

    protected string $sortByValue;

    protected string $sortDirField = 'sort_dir';

    protected string $sortDirValue;

    protected string $searchField = 'search';

    protected string $searchValue;

    /**
     * Redefine table interaction fields from identifier.
     *
     * @return void
     */
    protected function redefineInteractionFieldsFromIdentifier(): void
    {
        $underscoredIdentifier = $this->getIdentifier() ? str_replace('-', '_', $this->getIdentifier()) . '_' : '';
        $this->rowsField = $underscoredIdentifier . $this->rowsField;
        $this->searchField = $underscoredIdentifier . $this->searchField;
        $this->sortByField = $underscoredIdentifier . $this->sortByField;
        $this->sortDirField = $underscoredIdentifier . $this->sortDirField;
    }

    /**
     * Handle the request interactions sent values.
     *
     * @return void
     */
    protected function handleRequestInteractionValues(): void
    {
        $validator = Validator::make($this->request->only(
            $this->rowsField,
            $this->searchField,
            $this->sortByField,
            $this->sortDirField
        ), [
            $this->rowsField => ['required', 'integer'],
            $this->searchField => ['nullable', 'string'],
            $this->sortByField => [
                'nullable',
                'string',
                'in:' . $this->getColumns()->implode('databaseDefaultColumn', ','),
            ],
            $this->sortDirField => ['nullable', 'string', 'in:asc,desc'],
        ]);
        if ($validator->fails()) {
            $this->request->merge([
                $this->rowsField => $this->rowsValue ?? config('laravel-table.value.rows'),
                $this->searchField => null,
                $this->sortByField => $this->getSortByValue(),
                $this->sortDirField => $this->getSortDirValue(),
            ]);
        }
        $this->rowsValue = $this->request->get($this->rowsField);
        $this->searchValue = $this->request->get($this->searchField);
    }

    /**
     * Apply sort clauses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function applySortClauses(Builder $query): void
    {
        $this->sortByValue = $this->request->get($this->sortByField)
            ?: ($this->getSortByValue() ?: optional($this->getSortableColumns()->first())->databaseDefaultColumn);
        $this->sortDirValue = $this->request->get($this->sortDirField)
            ?: ($this->getSortDirValue() ?: 'asc');
        if ($this->getSortByValue() && $this->getSortDirValue()) {
            $query->orderBy($this->getSortByValue(), $this->getSortDirValue());
        }
    }

    /**
     * Apply search clauses
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    protected function applySearchClauses(Builder $query): void
    {
        $searched = $this->request->get($this->searchField);
        if ($searched) {
            $query->where(function (Builder $subQuery) use ($searched) {
                $this->getSearchableColumns()->map(function (
                    Column $column,
                    int $columnKey
                ) use (
                    $subQuery,
                    $searched
                ) {
                    $databaseSearchedTable = $column->databaseSearchedTable ?: $column->databaseDefaultTable;
                    $whereOperator = $columnKey > 0 ? 'orWhere' : 'where';
                    $databaseSearchedColumns = $column->databaseSearchedColumns ?: [$column->databaseDefaultColumn];
                    foreach ($databaseSearchedColumns as $searchedDatabaseColumnKey => $searchedDatabaseColumn) {
                        $whereOperator = $searchedDatabaseColumnKey > 0 ? 'orWhere' : $whereOperator;
                        $subQuery->{$whereOperator}(
                            $databaseSearchedTable . '.' . $searchedDatabaseColumn,
                            $this->casInsensitiveLikeOperator(),
                            '%' . $searched . '%'
                        );
                    }
                });
            });
        }
    }

    /**
     * Get insensitive like operator according to the used database driver.
     *
     * @return string
     */
    protected function casInsensitiveLikeOperator(): string
    {
        $connection = config('database.default');
        $driver = config('database.connections.' . $connection . '.driver');

        return in_array($driver, ['pgsql']) ? 'ILIKE' : 'LIKE';
    }
}
