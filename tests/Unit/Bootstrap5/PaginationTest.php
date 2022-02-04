<?php

namespace Okipa\LaravelTable\Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Tests\Models\User;
use Okipa\LaravelTable\Tests\TestCase;

class PaginationTest extends TestCase
{
    /** @test */
    public function it_can_set_a_global_default_number_of_rows_per_page_from_config(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 10);
        $table = app(Table::class)->model(User::class);
        $table->generateRows();
        $this->assertEquals(10, $table->getRows()->perPage());
    }

    /** @test */
    public function it_can_set_a_specific_default_number_of_rows_per_page_from_table(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 10);
        $table = app(Table::class)->model(User::class)->numberOfRowsPerPage(5);
        $table->generateRows();
        $this->assertEquals(5, $table->getRows()->perPage());
    }

    /** @test */
    public function it_cant_paginate_table_when_number_of_rows_does_not_exceed_the_limit(): void
    {
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
            ->assertDontSeeHtml(['<ul class="pagination">']);
    }

    /** @test */
    public function it_can_paginate_table_when_number_of_rows_does_exceed_the_limit(): void
    {
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
                '<ul class="pagination">'
            ]);
    }
}
