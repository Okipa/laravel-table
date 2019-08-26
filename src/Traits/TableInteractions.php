<?php

namespace Okipa\LaravelTable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelTable\Column;

trait TableInteractions
{
    /** @property int $rows */
    public $rows;
    /** @property bool $rowsField */
    public $rowsField = 'rows';
    /** @property string $sortBy */
    public $sortBy;
    /** @property string $sortByField */
    public $sortByField = 'sort_by';
    /** @property string $sortDir */
    public $sortDir;
    /** @property string $sortDirField */
    public $sortDirField = 'sort_dir';
    /** @property string $search */
    public $search;
    /** @property string $searchField */
    public $searchField = 'search';

    /**
     * Redefine table interaction fields from identifier.
     *
     * @return void
     */
    protected function redefineInteractionFieldsFromIdentifier(): void
    {
        $underscoredIdentifier = $this->identifier ? str_replace('-', '_', $this->identifier) . '_' : '';
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
            $this->rowsField    => 'required|integer',
            $this->searchField  => 'nullable|string',
            $this->sortByField  => 'nullable|string|in:' . $this->columns->implode('databaseDefaultColumn', ','),
            $this->sortDirField => 'nullable|string|in:asc,desc',
        ]);
        if ($validator->fails()) {
            $this->request->merge([
                $this->rowsField    => $this->rows ?? config('laravel-table.value.rows'),
                $this->searchField  => null,
                $this->sortByField  => $this->sortBy,
                $this->sortDirField => $this->sortDir,
            ]);
        }
        $this->rows = $this->request->get($this->rowsField);
        $this->search = $this->request->get($this->searchField);
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
        $this->sortBy = $this->request->get($this->sortByField)
            ?: ($this->sortBy ? $this->sortBy : optional($this->sortableColumns->first())->databaseDefaultColumn);
        $this->sortDir = $this->request->get($this->sortDirField)
            ?: ($this->sortDir ? $this->sortDir : 'asc');
        if ($this->sortBy && $this->sortDir) {
            $query->orderBy($this->sortBy, $this->sortDir);
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
                $this->searchableColumns->map(function (Column $column, int $columnKey) use ($subQuery, $searched) {
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
