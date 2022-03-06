<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\RowActions\Edit;
use Okipa\LaravelTable\RowActions\Show;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableRowActionTest extends TestCase
{
    /** @test */
    public function it_can_set_table_row_actions(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);

        Config::set('laravel-table.icon.edit', 'edit-icon');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn(Model $model) => [
                    new Show('https://test-url.com'),
                    new Edit('https://test-url.com'),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr class="border-bottom">',
                '<td class="align-middle">',
                '<a wire:click.prevent="rowAction(\'edit\')"',
                'class="btn btn-link p-0"',
                'href=""',
                'title="Edit">',
                'edit-icon',
                '</a>',
                '</td>',
                '</tr>',
                '</tbody>',
            ])
            ->call('rowAction', 'edit')
            ->assertRedirect('https://test-url.com');
    }
}
