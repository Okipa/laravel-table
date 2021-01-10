<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait HasClasses
{
    protected array $containerClasses;

    protected array $tableClasses;

    protected array $trClasses;

    protected array $thClasses;

    protected array $tdClasses;

    protected Collection $rowsConditionalClasses;

    public function containerClasses(array $containerClasses): Table
    {
        $this->containerClasses = $containerClasses;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getContainerClasses(): array
    {
        return $this->containerClasses;
    }

    public function tableClasses(array $tableClasses): Table
    {
        $this->tableClasses = $tableClasses;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTableClasses(): array
    {
        return $this->tableClasses;
    }

    public function trClasses(array $trClasses): Table
    {
        $this->trClasses = $trClasses;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTrClasses(): array
    {
        return $this->trClasses;
    }

    public function thClasses(array $thClasses): Table
    {
        $this->thClasses = $thClasses;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getThClasses(): array
    {
        return $this->thClasses;
    }

    public function tdClasses(array $tdClasses): Table
    {
        $this->tdClasses = $tdClasses;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTdClasses(): array
    {
        return $this->tdClasses;
    }

    /**
     * @param \Closure $conditions
     * @param array|\Closure $classes
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsConditionalClasses(Closure $conditions, $classes): Table
    {
        $this->rowsConditionalClasses->push(['conditions' => $conditions, 'classes' => $classes]);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    protected function initializeTableDefaultClasses(): void
    {
        $this->containerClasses = config('laravel-table.classes.container');
        $this->tableClasses = config('laravel-table.classes.table');
        $this->trClasses = config('laravel-table.classes.tr');
        $this->thClasses = config('laravel-table.classes.th');
        $this->tdClasses = config('laravel-table.classes.td');
        $this->rowsConditionalClasses = new Collection();
    }

    protected function addClassesToRow(Model $model): void
    {
        $this->getRowsConditionalClasses()->each(function (array $row) use ($model) {
            if ($row['conditions']($model)) {
                $model->conditionnal_classes = array_merge(
                    $model->conditionnal_classes ?? [],
                    is_callable($row['classes']) ? $row['classes']($model) : $row['classes']
                );
            }
        });
    }

    public function getRowsConditionalClasses(): Collection
    {
        return $this->rowsConditionalClasses;
    }
}
