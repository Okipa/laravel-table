<?php

namespace Okipa\LaravelTable\Traits;

use Okipa\LaravelTable\Table;

trait TableTemplatesCustomizations
{
    /** @property string $tableTemplatePath */
    public $tableTemplatePath;

    /** @property string $theadTemplatePath */
    public $theadTemplatePath;

    /** @property string $tbodyTemplatePath */
    public $tbodyTemplatePath;

    /** @property string $showTemplatePath */
    public $showTemplatePath;

    /** @property string $editTemplatePath */
    public $editTemplatePath;

    /** @property string $destroyTemplatePath */
    public $destroyTemplatePath;

    /** @property string $resultsComponentPath */
    public $resultsComponentPath;

    /** @property string $tfootComponentPath */
    public $tfootComponentPath;

    /**
     * Set a custom template path for the table component.
     * The default table template path is defined in the config('laravel-table.template.table') config value.
     *
     * @param string $tableTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tableTemplate(string $tableTemplatePath): Table
    {
        $this->tableTemplatePath = $tableTemplatePath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the thead component.
     * The default thead template path is defined in the config('laravel-table.template.thead') config value.
     *
     * @param string $theadTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function theadTemplate(string $theadTemplatePath): Table
    {
        $this->theadTemplatePath = $theadTemplatePath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the tbody component.
     * The default tbody template path is defined in the config('laravel-table.template.tbody') config value.
     *
     * @param string $tbodyTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tbodyTemplate(string $tbodyTemplatePath): Table
    {
        $this->tbodyTemplatePath = $tbodyTemplatePath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the show component.
     * The default show template path is defined in the config('laravel-table.template.show') config value.
     *
     * @param string $showTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function showTemplate(string $showTemplatePath): Table
    {
        $this->showTemplatePath = $showTemplatePath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the edit component.
     * The default edit template path is defined in the config('laravel-table.template.edit') config value.
     *
     * @param string $editTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function editTemplate(string $editTemplatePath): Table
    {
        $this->editTemplatePath = $editTemplatePath;

        /** @var Table $this */
        return $this;
    }

    /**
     * Set a custom template path for the destroy component.
     * The default destroy template path is defined in the config('laravel-table.template.destroy') config value.
     *
     * @param string $destroyTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function destroyTemplate(string $destroyTemplatePath): Table
    {
        $this->destroyTemplatePath = $destroyTemplatePath;

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
        $this->tableTemplatePath = config('laravel-table.template.table', 'bootstrap.table');
        $this->theadTemplatePath = config('laravel-table.template.thead', 'bootstrap.thead');
        $this->tbodyTemplatePath = config('laravel-table.template.tbody', 'bootstrap.tbody');
        $this->showTemplatePath = config('laravel-table.template.show', 'bootstrap.show');
        $this->editTemplatePath = config('laravel-table.template.edit', 'bootstrap.edit');
        $this->destroyTemplatePath = config('laravel-table.template.destroy', 'bootstrap.destroy');
        $this->resultsComponentPath = config('laravel-table.template.results', 'bootstrap.results');
        $this->tfootComponentPath = config('laravel-table.template.tfoot', 'bootstrap.tfoot');
    }
}
