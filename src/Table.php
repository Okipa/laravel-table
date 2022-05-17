<?php

namespace Okipa\LaravelTable;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelTable\Traits\Table\HasAdditionalQueries;
use Okipa\LaravelTable\Traits\Table\HasClasses;
use Okipa\LaravelTable\Traits\Table\HasColumns;
use Okipa\LaravelTable\Traits\Table\HasDestroyConfirmation;
use Okipa\LaravelTable\Traits\Table\HasDisabledRows;
use Okipa\LaravelTable\Traits\Table\HasIdentifier;
use Okipa\LaravelTable\Traits\Table\HasModel;
use Okipa\LaravelTable\Traits\Table\HasPagination;
use Okipa\LaravelTable\Traits\Table\HasRequest;
use Okipa\LaravelTable\Traits\Table\HasResults;
use Okipa\LaravelTable\Traits\Table\HasRoutes;
use Okipa\LaravelTable\Traits\Table\HasRowsNumberDefinition;
use Okipa\LaravelTable\Traits\Table\HasSearching;
use Okipa\LaravelTable\Traits\Table\HasSorting;
use Okipa\LaravelTable\Traits\Table\HasTemplates;

class Table implements Htmlable
{
    use HasTemplates;
    use HasModel;
    use HasIdentifier;
    use HasRoutes;
    use HasClasses;
    use HasColumns;
    use HasResults;
    use HasRequest;
    use HasAdditionalQueries;
    use HasDisabledRows;
    use HasRowsNumberDefinition;
    use HasSorting;
    use HasSearching;
    use HasPagination;
    use HasDestroyConfirmation;

    protected bool $configured = false;

    public function __construct()
    {
        $this->initializeDefaultTemplates();
        $this->initializeTableDefaultClasses();
        $this->initializeColumns();
        $this->initializeResults();
        $this->initializeRequest();
        $this->initializeDisabledRows();
        $this->initializeRowsNumberDefinition();
    }

    public function hasBeenConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     * @throws \ErrorException
     */
    public function toHtml(): string
    {
        if (! $this->configured) {
            $this->configure();
        }

        return view('laravel-table::' . $this->getTableTemplatePath(), ['table' => $this])->toHtml();
    }

    /**
     * @throws \ErrorException
     */
    public function configure(): void
    {
        $this->checkRoutesValidity($this->routes);
        $this->checkIfAtLeastOneColumnIsDeclared();
        $this->defineInteractionsFromRequest();
        $query = $this->getModel()->query();
        $this->executeAdditionalQueries($query);
        $this->checkColumnsValidity($query);
        $this->applySearchingOnQuery($query);
        $this->applySortingOnQuery($query);
        $this->paginateFromQuery($query);
        $this->transformPaginatedRows();
        $this->configured = true;
    }

    protected function defineInteractionsFromRequest(): void
    {
        $validator = Validator::make($this->getRequest()->only(
            $this->getRowsNumberField(),
            $this->getSearchField(),
            $this->getSortByField(),
            $this->getSortDirField()
        ), [
            $this->getRowsNumberField() => ['required', 'integer'],
            $this->getSearchField() => ['nullable', 'string'],
            $this->getSortByField() => [
                'nullable',
                'string',
                'in:' . $this->getColumns()->map(fn(Column $column) => $column->getDbField())->implode(','),
            ],
            $this->getSortDirField() => ['nullable', 'string', 'in:asc,desc'],
        ]);
        if ($validator->fails()) {
            $this->getRequest()->merge([
                $this->getRowsNumberField() => $this->getRowsNumberValue(),
                $this->getSearchField() => $this->getRequest()->get($this->getSearchField()),
                $this->getSortByField() => $this->getSortByValue(),
                $this->getSortDirField() => $this->getSortDirValue(),
            ]);
        }
        $this->rowsNumberValue = $this->getRequest()->get($this->getRowsNumberField());
        $this->searchValue = $this->getRequest()->get($this->getSearchField());
    }
}
