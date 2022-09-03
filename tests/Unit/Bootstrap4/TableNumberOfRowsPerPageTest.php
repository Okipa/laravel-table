<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;

class TableNumberOfRowsPerPageTest extends \Tests\Unit\Bootstrap5\TableNumberOfRowsPerPageTest
{
    /** @test */
    public function it_can_set_global_default_number_of_rows_per_page_options_from_config(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_default_options', [1, 2, 3, 4, 5]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<div wire:ignore class="px-xl-3 py-1">',
                '<div class="input-group">',
                '<div class="input-group-prepend">',
                '<span id="rows-number-per-page-icon" class="input-group-text text-secondary">',
                'rows-number-icon',
                '</span>',
                '</div>',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon">',
                '<option wire:key="rows-number-per-page-option-placeholder" value="" disabled>Number of rows per page</option>',
                '<option wire:key="rows-number-per-page-option-1" value="1" selected>',
                '1',
                '</option>',
                '<option wire:key="rows-number-per-page-option-2" value="2">',
                '2',
                '</option>',
                '<option wire:key="rows-number-per-page-option-3" value="3">',
                '3',
                '</option>',
                '<option wire:key="rows-number-per-page-option-4" value="4">',
                '4',
                '</option>',
                '<option wire:key="rows-number-per-page-option-5" value="5">',
                '5',
                '</option>',
                '</select>',
                '</div>',
                '</div>',
                '</thead>',
            ]);
    }
}
