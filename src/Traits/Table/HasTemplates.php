<?php

namespace Okipa\LaravelTable\Traits\Table;

use Okipa\LaravelTable\Table;

trait HasTemplates
{
    protected string $tableTemplatePath;

    protected string $theadTemplatePath;

    protected string $rowsSearchingTemplatePath;

    protected string $rowsNumberDefinitionTemplatePath;

    protected string $createActionTemplatePath;

    protected string $columnTitlesTemplatePath;

    protected string $tbodyTemplatePath;

    protected string $showActionTemplatePath;

    protected string $editActionTemplatePath;

    protected string $destroyActionTemplatePath;

    protected string $resultsTemplatePath;

    protected string $tfootTemplatePath;

    protected string $navigationStatusTemplatePath;

    protected string $paginationTemplatePath;

    public function tableTemplate(string $tableTemplatePath): Table
    {
        $this->tableTemplatePath = $tableTemplatePath;

        return $this;
    }

    public function getTableTemplatePath(): string
    {
        return $this->tableTemplatePath;
    }

    public function theadTemplate(string $theadTemplatePath): Table
    {
        $this->theadTemplatePath = $theadTemplatePath;

        return $this;
    }

    public function getTheadTemplatePath(): string
    {
        return $this->theadTemplatePath;
    }

    public function rowsSearchingTemplate(string $rowsSearchingTemplatePath): Table
    {
        $this->rowsSearchingTemplatePath = $rowsSearchingTemplatePath;

        return $this;
    }

    public function getRowsSearchingTemplatePath(): string
    {
        return $this->rowsSearchingTemplatePath;
    }

    public function rowsNumberDefinitionTemplate(string $rowsNumberDefinitionTemplatePath): Table
    {
        $this->rowsNumberDefinitionTemplatePath = $rowsNumberDefinitionTemplatePath;

        return $this;
    }

    public function getRowsNumberDefinitionTemplatePath(): string
    {
        return $this->rowsNumberDefinitionTemplatePath;
    }

    public function createActionTemplate(string $createActionTemplatePath): Table
    {
        $this->createActionTemplatePath = $createActionTemplatePath;

        return $this;
    }

    public function getCreateActionTemplatePath(): string
    {
        return $this->createActionTemplatePath;
    }

    public function columnTitlesTemplate(string $columnTitlesTemplatePath): Table
    {
        $this->columnTitlesTemplatePath = $columnTitlesTemplatePath;

        return $this;
    }

    public function getColumnTitlesTemplatePath(): string
    {
        return $this->columnTitlesTemplatePath;
    }

    public function tbodyTemplate(string $tbodyTemplatePath): Table
    {
        $this->tbodyTemplatePath = $tbodyTemplatePath;

        return $this;
    }

    public function getTbodyTemplatePath(): string
    {
        return $this->tbodyTemplatePath;
    }

    public function showActionTemplate(string $showActionTemplatePath): Table
    {
        $this->showActionTemplatePath = $showActionTemplatePath;

        return $this;
    }

    public function getShowActionTemplatePath(): string
    {
        return $this->showActionTemplatePath;
    }

    public function editActionTemplate(string $editActionTemplatePath): Table
    {
        $this->editActionTemplatePath = $editActionTemplatePath;

        return $this;
    }

    public function getEditActionTemplatePath(): string
    {
        return $this->editActionTemplatePath;
    }

    public function destroyActionTemplate(string $destroyActionTemplatePath): Table
    {
        $this->destroyActionTemplatePath = $destroyActionTemplatePath;

        return $this;
    }

    public function getDestroyActionTemplatePath(): string
    {
        return $this->destroyActionTemplatePath;
    }

    public function resultsTemplate(string $resultsTemplatePath): Table
    {
        $this->resultsTemplatePath = $resultsTemplatePath;

        return $this;
    }

    public function getResultsTemplatePath(): string
    {
        return $this->resultsTemplatePath;
    }

    public function tfootTemplate(string $tfootTemplatePath): Table
    {
        $this->tfootTemplatePath = $tfootTemplatePath;

        return $this;
    }

    public function getTfootTemplatePath(): string
    {
        return $this->tfootTemplatePath;
    }

    public function navigationStatusTemplate(string $navigationStatusTemplatePath): Table
    {
        $this->navigationStatusTemplatePath = $navigationStatusTemplatePath;

        return $this;
    }

    public function getNavigationStatusTemplatePath(): string
    {
        return $this->navigationStatusTemplatePath;
    }

    public function paginationTemplate(string $paginationTemplatePath): Table
    {
        $this->paginationTemplatePath = $paginationTemplatePath;

        return $this;
    }

    public function getPaginationTemplatePath(): string
    {
        return $this->paginationTemplatePath;
    }

    protected function initializeDefaultTemplates(): void
    {
        $this->tableTemplatePath = config('laravel-table.template.table');
        $this->theadTemplatePath = config('laravel-table.template.thead');
        $this->rowsSearchingTemplatePath = config('laravel-table.template.rows_searching');
        $this->rowsNumberDefinitionTemplatePath = config('laravel-table.template.rows_number_definition');
        $this->createActionTemplatePath = config('laravel-table.template.create_action');
        $this->columnTitlesTemplatePath = config('laravel-table.template.column_titles');
        $this->tbodyTemplatePath = config('laravel-table.template.tbody');
        $this->showActionTemplatePath = config('laravel-table.template.show_action');
        $this->editActionTemplatePath = config('laravel-table.template.edit_action');
        $this->destroyActionTemplatePath = config('laravel-table.template.destroy_action');
        $this->resultsTemplatePath = config('laravel-table.template.results');
        $this->tfootTemplatePath = config('laravel-table.template.tfoot');
        $this->navigationStatusTemplatePath = config('laravel-table.template.navigation_status');
        $this->paginationTemplatePath = config('laravel-table.template.pagination');
    }
}
