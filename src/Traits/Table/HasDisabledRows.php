<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
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

        return $this;
    }

    protected function initializeDisabledRows(): void
    {
        $this->disabledRows = new Collection();
    }

    protected function disableRow(array &$row): void
    {
        foreach ($this->getDisabledRows() as $disabledRow) {
            $row['disabled_classes'] = (($disabledRow['closure'])($row) ? $disabledRow['classes'] : null);
        }
    }

    public function getDisabledRows(): Collection
    {
        return $this->disabledRows;
    }
}
