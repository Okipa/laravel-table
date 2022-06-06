<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\TestCase;

class ColumnSortableTest extends TestCase
{
    /** @test */
    public function it_cant_sort_any_column_when_no_column_is_sortable(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                    Column::make('Name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('sortBy', null)
            ->assertSet('sortDir', null)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                'Id',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                'Name',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id')->sortable(),
                    Column::make('Name')->sortable(),
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort descending"',
                'icon-sort-desc',
                '<span class="ms-2">Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id')->sortable(),
                    Column::make('Name')->sortable()->sortByDefault(),
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort descending"',
                'icon-sort-desc',
                '<span class="ms-2">Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id')->sortable(),
                    Column::make('Name')->sortable()->sortByDefault('desc'),
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort-asc',
                '<span class="ms-2">Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id')->sortable(),
                    Column::make('Name')->sortable(),
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort descending"',
                'icon-sort-desc',
                '<span class="ms-2">Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort-asc',
                '<span class="ms-2">Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Custom Id', 'id')->sortable(),
                    Column::make('Custom Name', 'name')->sortable(),
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Custom Id</span>',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'name\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort descending"',
                'icon-sort-desc',
                '<span class="ms-2">Custom Name</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->name . '</td>',
                $users->last()->name . '</td>',
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name')->sortable(),
                    Column::make('Companies count')
                        ->format(fn(User $user) => $user->companies->count())
                        ->sortable(fn(Builder $query, string $sortDir) => $query
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Name</span>',
                '</th>',
                '<th wire:key="column-companies-count" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'companies_count\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort descending"',
                'icon-sort-desc',
                '<span class="ms-2">Companies count</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->companies->count() . '</td>',
                $users->last()->companies->count() . '</td>',
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
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort',
                '<span class="ms-2">Name</span>',
                '</th>',
                '<th wire:key="column-companies-count" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'companies_count\')"',
                'class="d-flex align-items-center"',
                'href=""',
                'title="Sort ascending"',
                'icon-sort-asc',
                '<span class="ms-2">Companies count</span>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                $users->first()->companies->count() . '</td>',
                $users->last()->companies->count() . '</td>',
                '</tbody>',
            ]);
    }
}
