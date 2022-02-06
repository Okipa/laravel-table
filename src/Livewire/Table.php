<?php

namespace Okipa\LaravelTable\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Exceptions\InvalidTableConfiguration;

class Table extends Component
{
    use WithPagination;

    public string $config;

    public array $configParams = [];

    public \Okipa\LaravelTable\Table $table;

    public string $paginationTheme = 'bootstrap';

    public string|null $number_of_rows_per_page = null;

    public bool $initialized = false;

    protected $listeners = ['table:updated' => '$refresh'];

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
        return view('laravel-table::' . Config::get('laravel-table.ui') . '.table', $this->buildTable());
    }

    /**
     * @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration
     * @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared
     */
    protected function buildTable(): array
    {
        $config = $this->initConfig();
        $table = $this->initTable($config);
        $columns = $table->getColumns();

        return [
            'columns' => $columns,
            'columnsCount' => $columns->count(),
            'rows' => $table->getRows(),
            'navigationStatus' => $table->getNavigationStatus(),
        ];
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration */
    protected function initConfig(): AbstractTableConfiguration
    {
        if (! app($this->config) instanceof AbstractTableConfiguration) {
            throw new InvalidTableConfiguration('The given ' . $this->config
                . ' table config should extend ' . AbstractTableConfiguration::class . '.');
        }

        return app($this->config, $this->configParams);
    }

    protected function initTable(AbstractTableConfiguration $config): \Okipa\LaravelTable\Table
    {
        $table = app(\Okipa\LaravelTable\Table::class);
        $config->setup($table);
        $this->number_of_rows_per_page = $this->number_of_rows_per_page ?: $table->getNumberOfRowsPerPage();
        $table->generateRows();

        return $table;
    }
}
