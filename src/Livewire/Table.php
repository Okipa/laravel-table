<?php

namespace Okipa\LaravelTable\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
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

    public Collection $rowActions;

    protected $listeners = ['table:row:action:confirmed' => 'rowAction'];

    public function init(): void
    {
        $this->initPaginationTheme();
        $this->initialized = true;
    }

    protected function initPaginationTheme(): void
    {
        $this->paginationTheme = Str::contains(Config::get('laravel-table.ui'), 'bootstrap')
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

        return view('laravel-table::' . Config::get('laravel-table.ui') . '.table', $this->buildTable($config));
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration */
    protected function buildConfig(): AbstractTableConfiguration
    {
        if (! app($this->config) instanceof AbstractTableConfiguration) {
            throw new InvalidTableConfiguration($this->config);
        }

        return app($this->config, $this->configParams);
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
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
        // Generate
        $table->generateRows(
            $this->searchBy,
            $sortableClosure ?: $this->sortBy,
            $this->sortDir,
            $this->numberOfRowsPerPage,
        );
        // Row actions
        $this->rowActions = $table->generateActions();

        return [
            'columns' => $columns,
            'columnsCount' => $columns->count() + ($this->rowActions->isNotEmpty() ? 1 : 0),
            'rows' => $table->getRows(),
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

    public function rowAction(string $rowActionKey, mixed $primary, bool $requiresConfirmation): mixed
    {
        $rowAction = collect($this->rowActions->get($primary))->firstWhere('key', $rowActionKey);

        return $requiresConfirmation
            ? $this->emit('table:row:action:confirm', $rowActionKey, $primary, $rowAction->confirmationMessage)
            : $rowAction->action();
    }
}
