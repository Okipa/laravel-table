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
    //    /** @test */
    //    public function it_cant_search_any_column_when_no_column_is_searchable(): void
    //    {
    //        $users = User::factory()->count(2)->create();
    //        $config = new class extends AbstractTableConfiguration {
    //            protected function table(Table $table): void
    //            {
    //                $table->model(User::class);
    //            }
    //
    //            protected function columns(Table $table): void
    //            {
    //                $table->column('id');
    //                $table->column('name');
    //            }
    //        };
    //        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
    //            ->call('init')
    //            ->assertSet('sortBy', null)
    //            ->assertSet('sortAsc', false)
    //            ->assertSeeHtmlInOrder([
    //                '<thead>',
    //                '<tr',
    //                '<th class="align-middle" scope="col">',
    //                'id',
    //                '</th>',
    //                '<th class="align-middle" scope="col">',
    //                'name',
    //                '</th>',
    //                '</tr>',
    //                '</thead>',
    //                '<tbody>',
    //                e($users->first()->name),
    //                e($users->last()->name),
    //                '</tbody>',
    //            ])
    //            ->assertDontSeeHtml([
    //                '<a wire:click.prevent="sortBy(\'id\')"',
    //                'title="Sort descending"',
    //                '<a wire:click.prevent="sortBy(\'name\')"',
    //                'title="Sort ascending"',
    //            ]);
    //    }

    /** @test */
    public function it_can_search(): void
    {
        Config::set('laravel-table.icon.search', 'icon-search');
        Config::set('laravel-table.icon.reset', 'icon-reset');
        Config::set('laravel-table.icon.validate', 'icon-validate');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->searchable();
                $table->column('name')->searchable();
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('search', $users->first()->id)
            ->call('searchForRows')
            ->assertEmitted('search:executed')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                e($users->first()->name),
                '</tbody>',
            ])
            ->assertDontSeeHtml(e($users->first()->name));
    }
}
