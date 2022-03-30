<?php

namespace Okipa\LaravelTable\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Exceptions\InvalidTableConfiguration;

class Table extends Component
{
    use WithPagination;

    public bool $initialized = false;

    public string $config;

    public array $configParams = [];

    public \Okipa\LaravelTable\Table $table;

    public string $paginationTheme = 'bootstrap';

    public string $searchBy = '';

    public string $searchableLabels;

    public bool $searchInProgress;

    public int $numberOfRowsPerPage;

    public string|null $sortBy;

    public string|null $sortDir;

    public array|null $headActionArray;

    public array $tableRowActionsArray;

    public array $tableColumnActionsArray;

    protected $listeners = ['table:action:confirmed' => 'actionConfirmed'];

    public function init(): void
    {
        $this->initPaginationTheme();
        $this->initialized = true;
    }

    protected function initPaginationTheme(): void
    {
        $this->paginationTheme = Str::contains(config('laravel-table.ui'), 'bootstrap')
            ? 'bootstrap'
            : 'tailwind';
    }

    /**
     * @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration
     * @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared
     */
    public function render(): View
    {
        $config = $this->buildConfig();

        return view('laravel-table::' . config('laravel-table.ui') . '.table', $this->buildTable($config));
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration */
    protected function buildConfig(): AbstractTableConfiguration
    {
        if (! app($this->config) instanceof AbstractTableConfiguration) {
            throw new InvalidTableConfiguration($this->config);
        }

        return app($this->config, $this->configParams);
    }

    /**
     * @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared
     * @throws \JsonException
     */
    protected function buildTable(AbstractTableConfiguration $config): array
    {
        $table = $config->setup();
        $columns = $table->getColumns();
        // Search
        $this->searchableLabels = $table->getSearchableLabels();
        // Sort
        $columnSortedByDefault = $table->getColumnSortedByDefault();
        $this->sortBy = $this->sortBy ?? $columnSortedByDefault?->getKey();
        $this->sortDir = $this->sortDir ?? $columnSortedByDefault?->getSortDirByDefault();
        $sortableClosure = $this->sortBy
            ? $table->getColumn($this->sortBy)->getSortableClosure()
            : null;
        // Paginate
        $numberOfRowsPerPageOptions = $table->getNumberOfRowsPerPageOptions();
        $this->numberOfRowsPerPage = $this->numberOfRowsPerPage ?? Arr::first($numberOfRowsPerPageOptions);
        // Rows generation
        $table->generateRows(
            $this->searchBy,
            $sortableClosure ?: $this->sortBy,
            $this->sortDir,
            $this->numberOfRowsPerPage,
        );
        // Actions
        $this->headActionArray = $table->getHeadActionArray();
        $this->tableRowActionsArray = $table->generateRowActionsArray();
        $this->tableColumnActionsArray = $table->generateColumnActionsArray();

        return [
            'columns' => $columns,
            'columnsCount' => $columns->count() + ($this->tableRowActionsArray ? 1 : 0),
            'rows' => $table->getRows(),
            'tableRowClass' => $table->getRowClass(),
            'numberOfRowsPerPageChoiceEnabled' => $table->isNumberOfRowsPerPageChoiceEnabled(),
            'numberOfRowsPerPageOptions' => $numberOfRowsPerPageOptions,
            'navigationStatus' => $table->getNavigationStatus(),
        ];
    }

    public function changeNumberOfRowsPerPage(int $numberOfRowsPerPage): void
    {
        $this->numberOfRowsPerPage = $numberOfRowsPerPage;
    }

    public function sortBy(string $columnKey): void
    {
        $this->sortDir = $this->sortBy !== $columnKey || $this->sortDir === 'desc'
            ? 'asc'
            : 'desc';
        $this->sortBy = $columnKey;
    }

    public function headAction(): mixed
    {
        return AbstractHeadAction::make($this->headActionArray)->action($this);
    }

    public function rowAction(string $identifier, string $modelKey, bool $requiresConfirmation = false): mixed
    {
        $rowActionsArray = AbstractRowAction::retrieve($this->tableRowActionsArray, $modelKey);
        $rowActionArray = collect($rowActionsArray)->where('identifier', $identifier)->first();
        $rowActionInstance = AbstractRowAction::make($rowActionArray);
        if ($requiresConfirmation) {
            return $this->emit(
                'table:action:confirm',
                'rowAction',
                $identifier,
                $modelKey,
                $rowActionInstance->confirmationMessage
            );
        }
        $model = app($rowActionArray['modelClass'])->findOrFail($modelKey);
        $this->emit('table:action:executed', $rowActionInstance->getExecutedMessage());

        return $rowActionInstance->action($model, $this);
    }

    public function columnAction(string $identifier, string $modelKey, bool $requiresConfirmation = false): mixed
    {
        $columnActionArray = AbstractColumnAction::retrieve($this->tableColumnActionsArray, $modelKey, $identifier);
        $columnActionInstance = AbstractColumnAction::make($columnActionArray);
        if ($requiresConfirmation) {
            return $this->emit(
                'table:action:confirm',
                'columnAction',
                $identifier,
                $modelKey,
                $columnActionInstance->confirmationMessage
            );
        }
        $model = app($columnActionArray['modelClass'])->findOrFail($modelKey);
        $this->emit('table:action:executed', $columnActionInstance->getExecutedMessage());

        return $columnActionInstance->action($model, $identifier, $this);
    }

    public function actionConfirmed(string $actionType, string $identifier, string $modelKey): mixed
    {
        return $this->{$actionType}($identifier, $modelKey);
    }
}
