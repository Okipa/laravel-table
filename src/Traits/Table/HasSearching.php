<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Column;

trait HasSearching
{
    protected string $searchField = 'search';

    protected ?string $searchValue = null;

    protected function applySearchingOnQuery(Builder $query): void
    {
        $searched = $this->getRequest()->get($this->getSearchField());
        if ($searched) {
            $query->where(function (Builder $subQuery) use ($searched) {
                $this->getSearchableColumns()->map(function (
                    Column $column,
                    int $columnKey
                ) use (
                    $subQuery,
                    $searched
                ) {
                    $dbSearchedTable = $column->getDbSearchedTable() ?: $column->getDbTable();
                    $whereOperator = $columnKey > 0 ? 'orWhere' : 'where';
                    $dbSearchedFields = $column->getDbSearchedFields() ?: [$column->getDbField()];
                    foreach ($dbSearchedFields as $searchedDatabaseColumnKey => $searchedDatabaseColumn) {
                        $whereOperator = $searchedDatabaseColumnKey > 0 ? 'orWhere' : $whereOperator;
                        $subQuery->{$whereOperator}(
                            $dbSearchedTable . '.' . $searchedDatabaseColumn,
                            $this->getCaseInsensitiveSearchingLikeOperator(),
                            '%' . $searched . '%'
                        );
                    }
                });
            });
        }
    }

    public function getSearchField(): string
    {
        return $this->searchField;
    }

    protected function getCaseInsensitiveSearchingLikeOperator(): string
    {
        $connection = config('database.default');
        $driver = config('database.connections.' . $connection . '.driver');

        return in_array($driver, ['pgsql']) ? 'ILIKE' : 'LIKE';
    }
}
