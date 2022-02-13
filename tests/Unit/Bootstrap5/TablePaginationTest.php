<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TablePaginationTest extends TestCase
{
    /** @test */
    public function it_cant_paginate_table_when_number_of_rows_does_not_exceed_the_limit(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 10);
        User::factory()->count(10)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('paginationTheme', 'bootstrap')
            ->assertDontSeeHtml('<ul class="pagination">');
    }

    /** @test */
    public function it_can_paginate_table_when_number_of_rows_does_exceed_the_limit(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 10);
        User::factory()->count(15)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('paginationTheme', 'bootstrap')
            ->assertSeeHtmlInOrder([
                '<tfoot>',
                '<ul class="pagination">',
                '</tfoot>',
            ]);
    }
}
