<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnSortableTest extends TestCase
{
    /** @test */
    public function it_cant_sort_any_column_when_no_column_is_sortable(): void
    {
        $users = User::factory()->count(2)->create();
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
            ->assertSet('sortBy', null)
            ->assertSet('sortAsc', false)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th class="align-middle" scope="col">',
                'id',
                '</th>',
                '<th class="align-middle" scope="col">',
                'name',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
                '</tbody>',
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
        $users = User::factory()->count(2)->create();
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
            ->assertSet('sortBy', 'id')
            ->assertSet('sortAsc', true)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
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
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_asc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
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
        $users = $users->sortBy('name');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortAsc', true)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
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
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_desc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
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
        $users = $users->sortByDesc('name');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortAsc', false)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
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
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_from_column(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
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
        $users = $users->sortBy('name');
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'id')
            ->assertSet('sortAsc', true)
            ->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortAsc', true)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
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
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
            ]);
        $users = $users->sortByDesc('name');
        $component->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortAsc', false)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
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
                '</tr>',
                '</thead>',
                '<tbody>',
                e($users->first()->name),
                e($users->last()->name),
                '</tbody>',
            ]);
    }
}
