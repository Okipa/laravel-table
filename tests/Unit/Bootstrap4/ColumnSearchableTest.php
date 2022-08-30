<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;

class ColumnSearchableTest extends \Tests\Unit\Bootstrap5\ColumnSearchableTest
{
    /** @test */
    public function it_can_display_search_form_with_searchable_columns(): void
    {
        Config::set('laravel-table.icon.search', 'icon-search');
        Config::set('laravel-table.icon.reset', 'icon-reset');
        Config::set('laravel-table.icon.validate', 'icon-validate');
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
                    Column::make('name')->searchable(),
                    Column::make('email')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<form wire:submit.prevent="$refresh">',
                '<div class="input-group">',
                '<div class="input-group-prepend">',
                '<span id="search-for-rows" class="input-group-text">',
                'icon-search',
                '</span>',
                '</div>',
                '<input wire:model.defer="searchBy"',
                'class="form-control"',
                'placeholder="Search by: validation.attributes.name, validation.attributes.email"',
                'aria-label="Search by: validation.attributes.name, validation.attributes.email"',
                'aria-describedby="search-for-rows">',
                '<div class="input-group-append">',
                '<span class="input-group-text">',
                '<button class="btn btn-sm btn-link link-primary p-0"',
                'type="submit"',
                'title="Search by: validation.attributes.name, validation.attributes.email">',
                'icon-validate',
                '</button>',
                '</span>',
                '</div>',
                '</thead>',
            ]);
    }
}
