<?php

namespace Okipa\LaravelTable\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Exceptions\InvalidTableConfiguration;

class Table extends Component
{
    use WithPagination;

    public string $config;

    protected bool $initialized = false;

    public string $paginationTheme = 'bootstrap';

    public function init(): void
    {
        $this->paginationTheme = Str::contains(config('laravel-table.ui'), 'bootstrap')
            ? 'bootstrap'
            : 'tailwind';
        $this->initialized = true;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration */
    public function render(): View
    {
        $table = $this->initialized ? $this->configure() : null;

        return view('laravel-table::' . config('laravel-table.ui') . '.table', compact('table'));
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidTableConfiguration */
    public function configure(): \Okipa\LaravelTable\Table|null
    {
        $config = app($this->config);
        if (! $config instanceof AbstractTableConfiguration) {
            throw new InvalidTableConfiguration('The given ' . $this->config
                . ' table config should extend ' . AbstractTableConfiguration::class . '.');
        }
        $table = app(\Okipa\LaravelTable\Table::class);
        $config->setup($table);
        $table->generateRows();

        return $table;
    }
}
