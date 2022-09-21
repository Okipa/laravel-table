<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;

class ColumnSortableTest extends \Tests\Unit\Bootstrap5\ColumnSortableTest
{
    /** @test */
    public function it_can_sort_first_sortable_column_when_no_column_is_sorted_by_default(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id')->sortable(),
                    Column::make('name')->sortable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'id')
            ->assertSet('sortDir', 'asc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                ' data-toggle="tooltip">',
                'icon-sort-desc',
                '<span class="ml-2">validation.attributes.id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_asc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id')->sortable(),
                    Column::make('name')->sortable()->sortByDefault(),
                ];
            }
        };
        $users = $users->sortBy('name');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDir', 'asc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                ' data-toggle="tooltip">',
                'icon-sort-desc',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_desc_column_by_default(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id')->sortable(),
                    Column::make('name')->sortable()->sortByDefault('desc'),
                ];
            }
        };
        $users = $users->sortByDesc('name');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDir', 'desc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort-asc',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_column(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id')->sortable(),
                    Column::make('name')->sortable(),
                ];
            }
        };
        $users = $users->sortBy('name');
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'id')
            ->assertSet('sortDir', 'asc')
            ->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDir', 'asc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                ' data-toggle="tooltip">',
                'icon-sort-desc',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
        $users = $users->sortByDesc('name');
        $component->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDir', 'desc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort-asc',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_column_from_custom_key(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id')->title('Custom Id')->sortable(),
                    Column::make('name')->title('Custom Name')->sortable(),
                ];
            }
        };
        $users = $users->sortBy('name');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'id')
            ->assertSet('sortDir', 'asc')
            ->call('sortBy', 'name')
            ->assertSet('sortBy', 'name')
            ->assertSet('sortDir', 'asc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'id\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">Custom Id</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                ' data-toggle="tooltip">',
                'icon-sort-desc',
                '<span class="ml-2">Custom Name</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_sort_specific_column_from_closure(): void
    {
        Config::set('laravel-table.icon.sort_asc', 'icon-sort-asc');
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.sort', 'icon-sort');
        $users = User::factory()->count(2)->create();
        Company::factory()->count(6)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name')->sortable(),
                    Column::make('companies_count')
                        ->format(fn (User $user) => $user->companies->count())
                        ->sortable(fn (Builder $query, string $sortDir) => $query
                            ->withCount('companies')
                            ->orderBy('companies_count', $sortDir))
                        ->sortByDefault(),
                ];
            }
        };
        $users = $users->loadCount('companies')->sortBy('companies_count');
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', 'companies_count')
            ->assertSet('sortDir', 'asc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-companies-count" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'companies_count\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                ' data-toggle="tooltip">',
                'icon-sort-desc',
                '<span class="ml-2">validation.attributes.companies_count</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->companies->count(),
                $users->last()->companies->count(),
                '</tbody>',
            ]);
        $users = $users->sortByDesc('companies_count');
        $component->call('sortBy', 'companies_count')
            ->assertSet('sortBy', 'companies_count')
            ->assertSet('sortDir', 'desc')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort',
                '<span class="ml-2">validation.attributes.name</span>',
                '</a>',
                '</th>',
                '<th wire:key="column-companies-count" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'companies_count\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort ascending"',
                ' data-toggle="tooltip">',
                'icon-sort-asc',
                '<span class="ml-2">validation.attributes.companies_count</span>',
                '</a>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->companies->count(),
                $users->last()->companies->count(),
                '</tbody>',
            ]);
    }
}
