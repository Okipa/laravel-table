<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnSearchableTest extends TestCase
{
    /** @test */
    public function it_cant_display_search_form_when_no_column_is_searchable(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
                $table->column('Name')->searchable();
                $table->column('Email')->searchable();
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
                $table->column('Name')->searchable();
                $table->column('Email')->searchable();
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('search', '')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ])
            ->set('search', $users->first()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->last()->name)
            ->set('search', $users->last()->email)
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
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
                $table->column('Name')->searchable();
                $table->column('Email')->searchable();
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('search', $users->first()->name)
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml($users->last()->name)
            ->set('search', '')
            ->call('$refresh')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                $users->last()->name,
                '</tbody>',
            ]);
    }
}
