<?php

namespace Okipa\LaravelTable\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Okipa\LaravelTable\Abstracts\AbstractBulkAction;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Exceptions\InvalidTableConfiguration;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
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

    public array $selectedFilters = [];

    public array|null $headActionArray;

    public bool $selectAll = false;

    public array $selectedModelKeys = [];

    public array $tableBulkActionsArray;

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
     * @throws \JsonException
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
        $config = app($this->config);
        foreach ($this->configParams as $key => $value) {
            $config->{$key} = $value;
        }

        return $config;
    }

    /**
     * @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared
     * @throws \JsonException
     */
    protected function buildTable(AbstractTableConfiguration $config): array
    {
        $table = $config->setup();
        $columns = $table->getColumns();
        // Events triggering in load
        $table->triggerEventsEmissionOnLoad($this);
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
        // Filters
        $filtersArray = $table->generateFiltersArray();
        $filterClosures = $table->getFilterClosures($filtersArray, $this->selectedFilters);
        // Query preparation
        $query = $table->prepareQuery(
            $filterClosures,
            $this->searchBy,
            $sortableClosure ?: $this->sortBy,
            $this->sortDir,
        );
        // Rows generation
        $table->paginateRows($query, $this->numberOfRowsPerPage);
        // Results computing
        $table->computeResults($table->getRows()->getCollection());
        // Head action
        $this->headActionArray = $table->getHeadActionArray();
        // Bulk actions
        if (in_array($this->selectedModelKeys, [['selectAll'], ['unselectAll']], true)) {
            $this->selectedModelKeys = $this->selectedModelKeys === ['selectAll']
                ? $table->getRows()->pluck('id')->map(fn(int $id) => (string) $id)->toArray()
                : [];
        }
        $this->tableBulkActionsArray = $table->generateBulkActionsArray($this->selectedModelKeys);
        // Row actions
        $this->tableRowActionsArray = $table->generateRowActionsArray();
        // Column actions
        $this->tableColumnActionsArray = $table->generateColumnActionsArray();

        return [
            'columns' => $columns,
            'columnsCount' => ($this->tableBulkActionsArray ? 1 : 0)
                + $columns->count()
                + ($this->tableRowActionsArray ? 1 : 0),
            'rows' => $table->getRows(),
            'filtersArray' => $filtersArray,
            'numberOfRowsPerPageChoiceEnabled' => $table->isNumberOfRowsPerPageChoiceEnabled(),
            'numberOfRowsPerPageOptions' => $numberOfRowsPerPageOptions,
            'tableRowClass' => $table->getRowClass(),
            'results' => $table->getResults(),
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

    public function updatedSelectAll(): void
    {
        $this->selectedModelKeys = $this->selectAll ? ['selectAll'] : ['unselectAll'];
    }

    public function actionConfirmed(string $actionType, string $identifier, string|null $modelKey): mixed
    {
        return match ($actionType) {
            'bulkAction' => $this->bulkAction($identifier, false),
            'rowAction' => $this->rowAction($identifier, $modelKey, false),
            'columnAction' => $this->columnAction($identifier, $modelKey, false),
        };
    }

    public function bulkAction(string $identifier, bool $requiresConfirmation): mixed
    {
        $bulkActionArray = AbstractBulkAction::retrieve($this->tableBulkActionsArray, $identifier);
        $bulkActionInstance = AbstractBulkAction::make($bulkActionArray);
        if (! $bulkActionInstance->allowedModelKeys) {
            return null;
        }
        if ($requiresConfirmation) {
            return $this->emit(
                'table:action:confirm',
                'bulkAction',
                $identifier,
                null,
                $bulkActionInstance->getConfirmationQuestion()
            );
        }
        $feedbackMessage = $bulkActionInstance->getFeedbackMessage();
        if ($feedbackMessage) {
            $this->emit('table:action:feedback', $feedbackMessage);
        }
        $models = app($bulkActionInstance->modelClass)->findMany($bulkActionInstance->allowedModelKeys);

        return $bulkActionInstance->action($models, $this);
    }

    public function rowAction(string $identifier, string $modelKey, bool $requiresConfirmation): mixed
    {
        $rowActionsArray = AbstractRowAction::retrieve($this->tableRowActionsArray, $modelKey);
        $rowActionArray = collect($rowActionsArray)->where('identifier', $identifier)->first();
        $rowActionInstance = AbstractRowAction::make($rowActionArray);
        $model = app($rowActionArray['modelClass'])->findOrFail($modelKey);
        if ($requiresConfirmation) {
            return $this->emit(
                'table:action:confirm',
                'rowAction',
                $identifier,
                $modelKey,
                $rowActionInstance->getConfirmationQuestion($model)
            );
        }
        $feedbackMessage = $rowActionInstance->getFeedbackMessage($model);
        if ($feedbackMessage) {
            $this->emit('table:action:feedback', $feedbackMessage);
        }

        return $rowActionInstance->action($model, $this);
    }

    public function columnAction(string $identifier, string $modelKey, bool $requiresConfirmation): mixed
    {
        $columnActionArray = AbstractColumnAction::retrieve($this->tableColumnActionsArray, $modelKey, $identifier);
        $columnActionInstance = AbstractColumnAction::make($columnActionArray);
        $model = app($columnActionArray['modelClass'])->findOrFail($modelKey);
        if ($requiresConfirmation) {
            return $this->emit(
                'table:action:confirm',
                'columnAction',
                $identifier,
                $modelKey,
                $columnActionInstance->getConfirmationQuestion($model, $identifier)
            );
        }
        $feedbackMessage = $columnActionInstance->getFeedbackMessage($model, $identifier);
        if ($feedbackMessage) {
            $this->emit('table:action:feedback', $feedbackMessage);
        }

        return $columnActionInstance->action($model, $identifier, $this);
    }
}
