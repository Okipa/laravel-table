<?php

namespace Okipa\LaravelTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Okipa\LaravelTable\Exceptions\NoColumnsDeclared;

class Table
{
    protected Model $model;

    protected Collection $columns;

    protected LengthAwarePaginator $rows;

    protected bool $numberOfRowsPerPageChoiceEnabled;

    protected array $numberOfRowsPerPageOptions;

    public function __construct()
    {
        $this->columns = collect();
        $this->numberOfRowsPerPageChoiceEnabled = Config::get('laravel-table.enable_number_of_rows_per_page_choice');
        $this->numberOfRowsPerPageOptions = Config::get('laravel-table.number_of_rows_per_page_options');
    }

    public function model(string $modelClass): self
    {
        $this->model = app($modelClass);

        return $this;
    }

    public function enableNumberOfRowsPerPageChoice(bool $numberOfRowsPerPageChoiceEnabled): bool
    {
        return $this->numberOfRowsPerPageChoiceEnabled = $numberOfRowsPerPageChoiceEnabled;
    }

    public function isNumberOfRowsPerPageChoiceEnabled(): bool
    {
        return $this->numberOfRowsPerPageChoiceEnabled;
    }

    public function numberOfRowsPerPageOptions(array $numberOfRowsPerPageOptions): self
    {
        $this->numberOfRowsPerPageOptions = $numberOfRowsPerPageOptions;

        return $this;
    }

    public function getNumberOfRowsPerPageOptions(): array
    {
        return $this->numberOfRowsPerPageOptions;
    }

    public function column(string $key): Column
    {
        $column = new Column($key);
        $this->columns->add($column);

        return $column;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getColumnSortedByDefault(): Column|null
    {
        $sortableColumns = $this->getColumns()->filter(fn(Column $column) => $column->isSortable());
        if ($sortableColumns->isEmpty()) {
            return null;
        }
        $columnSortedByDefault = $sortableColumns->filter(fn(Column $column) => $column->isSortedByDefault())->first();
        if (! $columnSortedByDefault) {
            return $sortableColumns->first();
        }

        return $columnSortedByDefault;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getColumns(): Collection
    {
        if ($this->columns->isEmpty()) {
            throw new NoColumnsDeclared('No columns are declared for ' . $this->model::class . ' table.');
        }

        return $this->columns;
    }

    public function generateRows(string|null $sortBy, bool $sortAsc, int $numberOfRowsPerPage): void
    {
        $this->rows = $this->model
            ->when($sortBy, fn(Builder $query) => $query->orderBy($sortBy, $sortAsc ? 'asc' : 'desc'))
            ->paginate($numberOfRowsPerPage);
    }

    public function getRows(): LengthAwarePaginator
    {
        return $this->rows;
    }

    public function getNavigationStatus(): string
    {
        return __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
            'start' => $this->rows->isNotEmpty()
                ? ($this->rows->perPage() * ($this->rows->currentPage() - 1)) + 1
                : 0,
            'stop' => $this->rows->count() + (($this->rows->currentPage() - 1) * $this->rows->perPage()),
            'total' => $this->rows->total(),
        ]);
    }
}
