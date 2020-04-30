<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait HasDisabledRows
{
    protected Collection $disabledRows;

    /**
     * Set the disable lines closure that will be executed during the table generation.
     * The optional second param let you override the classes that will be applied for the disabled lines.
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Model $model.
     *
     * @param \Closure $rowDisableClosure
     * @param array $classes
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function disableRows(Closure $rowDisableClosure, array $classes = []): Table
    {
        $this->disabledRows->push([
            'closure' => $rowDisableClosure,
            'classes' => ! empty($classes) ? $classes : config('laravel-table.classes.disabled'),
        ]);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getDisabledRows(): Collection
    {
        return $this->disabledRows;
    }

    protected function initializeDisabledRows(): void
    {
        $this->disabledRows = new Collection();
    }

    protected function disableRow(Model $model): void
    {
        $this->getDisabledRows()->each(function ($row) use ($model) {
            $model->disabledClasses = ($row['closure'])($model) ? $row['classes'] : null;
        });
    }
}
