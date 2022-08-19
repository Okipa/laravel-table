<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use PDOException;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\TestCase;

class ColumnSearchableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_display_search_form_when_no_column_is_searchable(): void
    {
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertDontSeeHtml('<form wire:submit.prevent="$refresh">');
    }

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
                    Column::make('Id'),
                    Column::make('Name')->searchable(),
                    Column::make('Email')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<form wire:submit.prevent="$refresh">',
                '<span id="search-for-rows"',
                'icon-search',
                'placeholder="Search by: Name, Email"',
                'aria-label="Search by: Name, Email"',
                'aria-describedby="search-for-rows"',
                '<button',
                'title="Search by: Name, Email"',
                'icon-validate',
                '</thead>',
            ]);
    }

    /** @test */
    public function it_can_search_from_model_data(): void
    {
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
                    Column::make('Id'),
                    Column::make('Name')->searchable(),
                    Column::make('Email')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('searchBy', '')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ])
            ->set('searchBy', $users->first()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->last()->name)
            ->set('searchBy', $users->last()->email)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->first()->name);
    }

    /** @test */
    public function it_can_search_from_closure(): void
    {
        $users = User::factory()->count(2)->create();
        $user1Companies = Company::factory()->withOwner($users->first())->count(3)->create();
        $user2Companies = Company::factory()->withOwner($users->last())->count(3)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name')->searchable(),
                    Column::make('Owned companies')
                        ->searchable(fn (Builder $query, string $searchBy) => $query->whereRelation(
                            'companies',
                            'name',
                            'LIKE',
                            '%' . $searchBy . '%'
                        )),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('searchBy', '')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ])
            ->set('searchBy', $user1Companies->first()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->last()->name)
            ->set('searchBy', $user2Companies->last()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->first()->name);
    }

    /** @test */
    public function it_can_reset_search(): void
    {
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
                    Column::make('Id'),
                    Column::make('Name')->searchable(),
                    Column::make('Email')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('searchBy', $users->first()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->last()->name)
            ->set('searchBy', '')
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_search_with_insensitive_case_with_postgres(): void
    {
        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('SQLSTATE[HY000]: General error: 1 near "ILIKE": syntax error (SQL: '
            . 'select count(*) as aggregate from "users" where LOWER(name) ILIKE %test%)');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name')->searchable(),
                ];
            }
        };
        $connection = config('database.default');
        Config::set('database.connections.' . $connection . '.driver', 'pgsql');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('searchBy', 'Test')
            ->call('$refresh');
    }
}
