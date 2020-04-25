<?php

namespace Okipa\LaravelTable\Traits;

use Closure;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait TableClassesCustomizations
{
    /** @property array $containerClasses */
    public $containerClasses;
    /** @property array $tableClasses */
    public $tableClasses;
    /** @property array $trClasses */
    public $trClasses;
    /** @property array $thClasses */
    public $thClasses;
    /** @property array $tdClasses */
    public $tdClasses;
    /** @property array $resultClasses */
    public $resultClasses;
    /** @property array $rowsConditionalClasses */
    public $rowsConditionalClasses;

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

        /** @var Table $this */
        return $this;
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

        /** @var Table $this */
        return $this;
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

        /** @var Table $this */
        return $this;
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

        /** @var Table $this */
        return $this;
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

        /** @var Table $this */
        return $this;
    }

    /**
     * Override default table result cells classes.
     *
     * @param array $resultClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function resultClasses(array $resultClasses): Table
    {
        $this->resultClasses = $resultClasses;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set rows classes when the given conditions are respected.
     * The closure let you manipulate the following attribute : \Illuminate\Database\Eloquent\Model $model.
     *
     * @param \Closure $rowClassesClosure
     * @param array    $rowClasses
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsConditionalClasses(Closure $rowClassesClosure, array $rowClasses): Table
    {
        $this->rowsConditionalClasses->push([
            'closure' => $rowClassesClosure,
            'classes' => $rowClasses,
        ]);

        /** @var Table $this */
        return $this;
    }

    /**
     * Initialize the default table classes from the config values.
     *
     * @return void
     */
    protected function initializeTableDefaultClasses(): void
    {
        $this->containerClasses = config('laravel-table.classes.container');
        $this->tableClasses = config('laravel-table.classes.table');
        $this->trClasses = config('laravel-table.classes.tr');
        $this->thClasses = config('laravel-table.classes.th');
        $this->tdClasses = config('laravel-table.classes.td');
        $this->resultClasses = config('laravel-table.classes.result');
        $this->rowsConditionalClasses = new Collection();
    }
}
