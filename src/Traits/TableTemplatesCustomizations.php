<?php

namespace Okipa\LaravelTable\Traits;

use Okipa\LaravelTable\Table;

trait TableTemplatesCustomizations
{
    /** @property string $tableComponentPath */
    public $tableComponentPath;
    /** @property string $theadComponentPath */
    public $theadComponentPath;
    /** @property string $tbodyComponentPath */
    public $tbodyComponentPath;
    /** @property string $resultsComponentPath */
    public $resultsComponentPath;
    /** @property string $tfootComponentPath */
    public $tfootComponentPath;

    /**
     * Set a custom template path for the table component.
     * The default table template path is defined in the config('laravel-table.template.table') config value.
     *
     * @param string $tableComponentPath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tableTemplate(string $tableComponentPath): Table
    {
        $this->tableComponentPath = $tableComponentPath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the thead component.
     * The default thead template path is defined in the config('laravel-table.template.thead') config value.
     *
     * @param string $theadComponentPath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function theadTemplate(string $theadComponentPath): Table
    {
        $this->theadComponentPath = $theadComponentPath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the tbody component.
     * The default tbody template path is defined in the config('laravel-table.template.tbody') config value.
     *
     * @param string $tbodyComponentPath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tbodyTemplate(string $tbodyComponentPath): Table
    {
        $this->tbodyComponentPath = $tbodyComponentPath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the results component.
     * The default results template path is defined in the config('laravel-table.template.results') config value.
     *
     * @param string $resultsComponentPath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function resultsTemplate(string $resultsComponentPath): Table
    {
        $this->resultsComponentPath = $resultsComponentPath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the tfoot component.
     * The default tfoot template path is defined in the config('laravel-table.template.tfoot') config value.
     *
     * @param string $tfootComponentPath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tfootTemplate(string $tfootComponentPath): Table
    {
        $this->tfootComponentPath = $tfootComponentPath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Initialize the default components from the config values.
     *
     * @return void
     */
    protected function initializeDefaultComponents(): void
    {
        $this->tableComponentPath = config('laravel-table.template.table');
        $this->theadComponentPath = config('laravel-table.template.thead');
        $this->tbodyComponentPath = config('laravel-table.template.tbody');
        $this->resultsComponentPath = config('laravel-table.template.results');
        $this->tfootComponentPath = config('laravel-table.template.tfoot');
    }
}
