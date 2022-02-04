<?php

namespace Okipa\LaravelTable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Table
{
    protected Model $model;

    protected Collection $columns;

    protected LengthAwarePaginator $rows;

    protected int $numberOfRowsPerPage;

    public function __construct()
    {
        $this->columns = collect();
        $this->numberOfRowsPerPage = config('laravel-table.number_of_rows_per_page');
    }

    public function model(string $modelClass): self
    {
        $this->model = app($modelClass);

        return $this;
    }

    public function numberOfRowsPerPage(int $numberOfRowsPerPage): self
    {
        $this->numberOfRowsPerPage = $numberOfRowsPerPage;

        return $this;
    }

    public function column(string $key): Column
    {
        $column = new Column($key);
        $this->columns->add($column);

        return $column;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function generateRows(): void
    {
        $this->rows = $this->model->paginate($this->numberOfRowsPerPage);
    }

    public function getRows(): LengthAwarePaginator
    {
        return $this->rows;
    }

    public function getNavigationStatus(): string
    {
        return __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
            'start' => ($this->rows->perPage() * ($this->rows->currentPage() - 1)) + 1,
            'stop' => $this->rows->count() + (($this->rows->currentPage() - 1) * $this->rows->perPage()),
            'total' => $this->rows->total(),
        ]);
    }
}
