<?php

namespace Okipa\LaravelTable\Traits\Table;

use Okipa\LaravelTable\Table;

trait HasTemplates
{
    protected string $tableTemplatePath;

    protected string $theadTemplatePath;

    protected string $tbodyTemplatePath;

    protected string $showTemplatePath;

    protected string $editTemplatePath;

    protected string $destroyTemplatePath;

    protected string $resultsTemplatePath;

    protected string $tfootTemplatePath;

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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTableTemplatePath(): string
    {
        return $this->tableTemplatePath;
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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTheadTemplatePath(): string
    {
        return $this->theadTemplatePath;
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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTbodyTemplatePath(): string
    {
        return $this->tbodyTemplatePath;
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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getShowTemplatePath(): string
    {
        return $this->showTemplatePath;
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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getEditTemplatePath(): string
    {
        return $this->editTemplatePath;
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

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getDestroyTemplatePath(): string
    {
        return $this->destroyTemplatePath;
    }

    /**
     * Set a custom template path for the results component.
     * The default results template path is defined in the config('laravel-table.template.results') config value.
     *
     * @param string $resultsTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function resultsTemplate(string $resultsTemplatePath): Table
    {
        $this->resultsTemplatePath = $resultsTemplatePath;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getResultsTemplatePath(): string
    {
        return $this->resultsTemplatePath;
    }

    /**
     * Set a custom template path for the tfoot component.
     * The default tfoot template path is defined in the config('laravel-table.template.tfoot') config value.
     *
     * @param string $tfootTemplatePath
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function tfootTemplate(string $tfootTemplatePath): Table
    {
        $this->tfootTemplatePath = $tfootTemplatePath;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getTfootTemplatePath(): string
    {
        return $this->tfootTemplatePath;
    }

    protected function initializeDefaultTemplates(): void
    {
        $this->tableTemplatePath = config('laravel-table.template.table', 'bootstrap.table');
        $this->theadTemplatePath = config('laravel-table.template.thead', 'bootstrap.thead');
        $this->tbodyTemplatePath = config('laravel-table.template.tbody', 'bootstrap.tbody');
        $this->showTemplatePath = config('laravel-table.template.show', 'bootstrap.show');
        $this->editTemplatePath = config('laravel-table.template.edit', 'bootstrap.edit');
        $this->destroyTemplatePath = config('laravel-table.template.destroy', 'bootstrap.destroy');
        $this->resultsTemplatePath = config('laravel-table.template.results', 'bootstrap.results');
        $this->tfootTemplatePath = config('laravel-table.template.tfoot', 'bootstrap.tfoot');
    }
}
