<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
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
                    Column::make('id'),
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
                '<tr>',
                '<td class="px-0" colspan="3">',
                '<div class="d-flex flex-column flex-xl-row">',
                '<div class="flex-fill">',
                '<div class="flex-fill pe-xl-3 py-1">',
                '<form wire:submit.prevent="$refresh">',
                '<div class="input-group">',
                '<span id="search-for-rows" class="input-group-text">',
                'icon-search',
                '</span>',
                '<input wire:model.defer="searchBy"',
                ' class="form-control"',
                ' placeholder="Search by: validation.attributes.name, validation.attributes.email"',
                ' aria-label="Search by: validation.attributes.name, validation.attributes.email"',
                ' aria-describedby="search-for-rows">',
                '<span class="input-group-text">',
                '<button class="btn btn-sm btn-link link-primary p-0"',
                ' type="submit"',
                ' title="Search by: validation.attributes.name, validation.attributes.email">',
                'icon-validate',
                '</button>',
                '</span>',
                '</div>',
                '</form>',
                '</div>',
                '</div>',
                '</div',
                '</td>',
                '</tr>',
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
                    Column::make('id'),
                    Column::make('name')->searchable(),
                    Column::make('email')->searchable(),
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
            ->assertDontSeeHtml([
                $users->last()->name,
            ])
            ->set('searchBy', $users->last()->email)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
            ]);
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
                    Column::make('name')->searchable(),
                    Column::make('companies')
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
            ->assertDontSeeHtml([
                $users->last()->name,
            ])
            ->set('searchBy', $user2Companies->last()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
            ]);
    }

    /** @test */
    public function it_can_search_with_a_query_defined(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $company1 = Company::factory()->withOwner($user1)->create();
        $company2 = Company::factory()->withOwner($user1)->create();
        $company3 = Company::factory()->withOwner($user2)->create();
        $company4 = Company::factory()->withOwner($user2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            public int $companyOwnerId;

            protected function table(): Table
            {
                return Table::make()
                    ->model(Company::class)
                    ->query(fn (Builder $query) => $query->whereRelation('owner', 'id', $this->companyOwnerId));
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                    Column::make('name')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, [
            'config' => $config::class,
            'configParams' => ['companyOwnerId' => $user1->id],
        ])
            ->call('init')
            ->assertSet('searchBy', '')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $company1->name,
                $company2->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $company3->name,
                $company4->name,
            ])
            ->set('searchBy', $company1->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $company1->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $company2->name,
                $company3->name,
                $company4->name,
            ]);
    }

    /** @test */
    public function it_can_search_case_insensitively(): void
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
                    Column::make('id'),
                    Column::make('name')->searchable(),
                    Column::make('email')->searchable(),
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
            ->set('searchBy', Str::lower($users->first()->name))
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->last()->name,
            ])
            ->set('searchBy', Str::upper($users->last()->email))
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
            ]);
    }

    /** @test */
    public function it_can_reset_search(): void
    {
        Config::set('laravel-table.icon.search', 'icon-search');
        Config::set('laravel-table.icon.validate', 'icon-validate');
        Config::set('laravel-table.icon.reset', 'icon-reset');
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
                    Column::make('id'),
                    Column::make('name')->searchable(),
                    Column::make('email')->searchable(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('searchBy', $users->first()->name)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr>',
                '<td class="px-0" colspan="3">',
                '<div class="d-flex flex-column flex-xl-row">',
                '<div class="flex-fill">',
                '<div class="flex-fill pe-xl-3 py-1">',
                '<form wire:submit.prevent="$refresh">',
                '<div class="input-group">',
                '<span id="search-for-rows" class="input-group-text">',
                'icon-search',
                '</span>',
                '<input wire:model.defer="searchBy"',
                ' class="form-control"',
                ' placeholder="Search by: validation.attributes.name, validation.attributes.email"',
                ' aria-label="Search by: validation.attributes.name, validation.attributes.email"',
                ' aria-describedby="search-for-rows">',
                '<span class="input-group-text">',
                '<button class="btn btn-sm btn-link link-primary p-0"',
                ' type="submit"',
                ' title="Search by: validation.attributes.name, validation.attributes.email">',
                'icon-validate',
                '</button>',
                '</span>',
                '<span class="input-group-text">',
                '<a wire:click.prevent="$set(\'searchBy\', \'\'), $refresh"',
                ' class="btn btn-sm btn-link link-secondary p-0"',
                ' title="Reset research">',
                'icon-reset',
                '</a>',
                '</span>',
                '</div>',
                '</form>',
                '</div>',
                '</div>',
                '</div',
                '</td>',
                '</tr>',
                '</thead>',
            ])
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->last()->name,
            ])
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
    public function it_can_execute_adapted_search_sql_statement_with_postgres(): void
    {
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/select count\(\*\) as aggregate from "users" where \(LOWER\(CAST\(name AS TEXT\)\) ILIKE %test%\)\)/');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name')->searchable(),
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
