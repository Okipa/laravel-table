<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait HasDisabledRows
{
    protected Collection $disabledRows;

    public function disableRows(Closure $rowDisableClosure, array $classes = []): Table
    {
        $this->disabledRows->push([
            'closure' => $rowDisableClosure,
            'classes' => ! empty($classes) ? $classes : config('laravel-table.classes.disabled'),
        ]);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function initializeDisabledRows(): void
    {
        $this->disabledRows = new Collection();
    }

    protected function disableRow(Model $model): void
    {
        $this->getDisabledRows()->each(
            fn($row) => $model->disabled_classes = (($row['closure'])($model)
                ? $row['classes']
                : null)
        );
    }

    public function getDisabledRows(): Collection
    {
        return $this->disabledRows;
    }
}
