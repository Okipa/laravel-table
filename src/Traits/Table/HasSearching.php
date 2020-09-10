<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelTable\Column;

trait HasSearching
{
    protected string $searchField = 'search';

    protected ?string $searchValue = null;

    abstract public function getRequest(): Request;

    abstract public function getSearchableColumns(): Collection;

    protected function applySearchingOnQuery(Builder $query): void
    {
        $searchedValue = $this->getRequest()->get($this->getSearchField());
        if (! $searchedValue) {
            return;
        }
        $query->where(function (Builder $subQuery) use ($searchedValue) {
            $this->searchOnColumns($subQuery, $searchedValue);
        });
    }

    protected function reDefineSearchField(string $searchField): void
    {
        $this->searchField = $searchField;
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }

    protected function searchOnColumns(Builder $query, string $searchedValue): void
    {
        $this->getSearchableColumns()->each(function (Column $column, int $columnKey) use ($query, $searchedValue) {
            $this->searchOnDbFields($query, $column, $columnKey, $searchedValue);
        });
    }

    protected function searchOnDbFields(Builder $query, Column $column, int $columnKey, string $searchedValue): void
    {
        $dbSearchedTable = $column->getDbSearchedTable() ?: $column->getDbTable();
        $whereOperator = $columnKey > 0 ? 'orWhere' : 'where';
        $dbSearchedFields = $column->getDbSearchedFields() ?: [$column->getDbField()];
        foreach ($dbSearchedFields as $searchedDatabaseColumnKey => $searchedDatabaseColumn) {
            $whereOperator = $searchedDatabaseColumnKey > 0 ? 'orWhere' : $whereOperator;
            $query->{$whereOperator}(
                // Allow to keep the case insensitive search with MySQL in case of JSON database field
                DB::raw('LOWER(' . $dbSearchedTable . '.' . $searchedDatabaseColumn . ')'),
                $this->getCaseInsensitiveSearchingLikeOperator(),
                '%' . mb_strtolower($searchedValue) . '%'
            );
        }
    }

    protected function getCaseInsensitiveSearchingLikeOperator(): string
    {
        $connection = config('database.default');
        $driver = config('database.connections.' . $connection . '.driver');

        return in_array($driver, ['pgsql']) ? 'ILIKE' : 'LIKE';
    }
}
