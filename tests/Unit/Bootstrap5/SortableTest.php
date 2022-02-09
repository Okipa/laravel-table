<?php

namespace Okipa\LaravelTable\Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Tests\Models\User;
use Okipa\LaravelTable\Tests\TestCase;

class SortableTest extends TestCase
{
    /** @test */
    public function it_cant_sort_any_column_when_no_column_is_sortable(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
                $table->column('name');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortedColumnKey', null)
            ->assertSet('sortedColumnAsc', false)
            ->assertSeeHtmlInOrder([
                '<th class="align-middle" scope="col">',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                'name',
                '</th>',
            ])
            ->assertDontSeeHtml([
                '<a wire:click.prevent="sortBy(\'id\')"',
                'title="Sort descending"',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'title="Sort ascending"',
            ]);
    }

    /** @test */
    public function it_can_sort_first_sortable_column_when_no_column_is_sorted_by_default(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->sortable();
                $table->column('name')->sortable();
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortedColumnKey', 'id')
            ->assertSet('sortedColumnAsc', true)
            ->assertSeeHtmlInOrder([
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                'title="Sort descending"',
                'icon-sort-desc',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'title="Sort ascending"',
                'icon-sort',
                'name',
                '</th>',
            ]);
    }

    /** @test */
    public function it_can_sort_asc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->sortable();
                $table->column('name')->sortable(true);
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortedColumnKey', 'name')
            ->assertSet('sortedColumnAsc', true)
            ->assertSeeHtmlInOrder([
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                'title="Sort ascending"',
                'icon-sort',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'title="Sort descending"',
                'icon-sort-desc',
                'name',
                '</th>',
            ]);
    }

    /** @test */
    public function it_can_sort_desc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->sortable();
                $table->column('name')->sortable(true, false);
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortedColumnKey', 'name')
            ->assertSet('sortedColumnAsc', false)
            ->assertSeeHtmlInOrder([
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                'title="Sort ascending"',
                'icon-sort',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'title="Sort ascending"',
                'icon-sort-asc',
                'name',
                '</th>',
            ]);
    }

    /** @test */
    public function it_can_sort_from_column(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->sortable(true);
                $table->column('name')->sortable();
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortedColumnKey', 'id')
            ->assertSet('sortedColumnAsc', true)
            ->call('sortBy', 'name')
            ->assertSet('sortedColumnKey', 'name')
            ->assertSet('sortedColumnAsc', true)
            ->assertSeeHtmlInOrder([
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                'title="Sort ascending"',
                'icon-sort',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'title="Sort descending"',
                'icon-sort-desc',
                'name',
                '</th>',
            ])
        ;
    }
}
