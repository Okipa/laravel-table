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

    /**
     * Override default table container classes.
     * The default container classes are defined in the config('laravel-table.classes.container') config value.
     *
     * @param array $containerClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
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

    /**
     * Override default table classes.
     * The default table classes are defined in the config('laravel-table.classes.table') config value.
     *
     * @param array $tableClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
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

    /**
     * Override default table tr classes.
     * The default tr classes are defined in the config('laravel-table.classes.tr') config value.
     *
     * @param array $trClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
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

    /**
     * Override default table th classes.
     * The default th classes are defined in the config('laravel-table.classes.th') config value.
     *
     * @param array $thClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
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

    /**
     * Override default table td classes.
     *
     * @param array $tdClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
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
     * Set rows classes when the given conditions are respected.
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Model $model.
     *
     * @param \Closure $rowClassesClosure
     * @param array $rowClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsConditionalClasses(Closure $rowClassesClosure, array $rowClasses): Table
    {
        $this->rowsConditionalClasses->push(['closure' => $rowClassesClosure, 'classes' => $rowClasses]);

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

    protected function addClassesToRow(Model $model)
    {
        $this->getRowsConditionalClasses()->each(function ($row) use ($model) {
            $model->conditionnalClasses = ($row['closure'])($model) ? $row['classes'] : null;
        });
    }

    public function getRowsConditionalClasses(): Collection
    {
        return $this->rowsConditionalClasses;
    }
}
