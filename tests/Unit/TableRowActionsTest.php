<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\RowActions\Destroy;
use Okipa\LaravelTable\RowActions\Edit;
use Okipa\LaravelTable\RowActions\Show;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableRowActionsTest extends TestCase
{
    /** @test */
    public function it_can_set_table_row_actions(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);
        Config::set('laravel-table.icon.show', 'show-icon');
        Config::set('laravel-table.icon.edit', 'edit-icon');
        Config::set('laravel-table.icon.destroy', 'destroy-icon');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn(User $user) => [
                    new Show(route('user.show', $user)),
                    new Edit(route('user.edit', $user)),
                    (new Destroy())
                        ->confirmationMessage('Are you sure you want to delete user ' . $user->name . '?')
                        ->executedMessage('User ' . $user->name . ' has been deleted.'),
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
                '<thead>',
                '<tr',
                '<th wire:key="column-actions" class="align-middle" scope="col">',
                'Actions',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<a wire:click.prevent="rowAction(\'show\', \'' . $users->first()->id . '\', 0)"',
                'class="link-info p-1"',
                'title="Show">',
                'show-icon',
                '<a wire:click.prevent="rowAction(\'edit\', \'' . $users->first()->id . '\', 0)"',
                'class="link-primary p-1"',
                'title="Edit">',
                'edit-icon',
                '<a wire:click.prevent="rowAction(\'destroy\', \'' . $users->first()->id . '\', 1)"',
                'class="link-danger p-1"',
                'title="Destroy">',
                'destroy-icon',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<a wire:click.prevent="rowAction(\'show\', \'' . $users->last()->id . '\', 0)"',
                'title="Show">',
                'show-icon',
                '<a wire:click.prevent="rowAction(\'edit\', \'' . $users->last()->id . '\', 0)"',
                'title="Edit">',
                'edit-icon',
                '<a wire:click.prevent="rowAction(\'destroy\', \'' . $users->last()->id . '\', 1)"',
                'title="Destroy">',
                'destroy-icon',
                '</tr>',
                '</tbody>',
            ])
            ->call('rowAction', 'show', $users->first()->id, false)
            ->assertRedirect(route('user.show', $users->first()))
            ->call('rowAction', 'edit', $users->last()->id, false)
            ->assertRedirect(route('user.edit', $users->last()))
            ->call('rowAction', 'destroy', $users->first()->id, true)
            ->assertEmitted(
                'table:action:confirm',
                'rowAction',
                'destroy',
                (string) $users->first()->id,
                'Are you sure you want to delete user ' . $users->first()->name . '?'
            )
            ->emit('table:action:confirmed', 'rowAction', 'destroy', $users->first()->id)
            ->assertEmitted('table:action:executed', 'User ' . $users->first()->name . ' has been deleted.');
        $this->assertDatabaseMissing('users', ['id' => $users->first()->id]);
    }

    /** @test */
    public function it_can_display_row_action_conditionally(): void
    {
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);
        Config::set('laravel-table.icon.edit', 'edit-icon');
        Config::set('laravel-table.icon.destroy', 'destroy-icon');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn(User $user) => [
                    new Edit(route('user.edit', $user)),
                    (new Destroy())->onlyWhen(fn(User $user) => ! Auth::user()->is($user)),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                ];
            }
        };
        Livewire::actingAs($users->first())
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<a wire:click.prevent="rowAction(\'edit\', \'' . $users->first()->id . '\', 0)"',
                'class="link-primary p-1"',
                'title="Edit">',
                'edit-icon',
                '<a wire:click.prevent="rowAction(\'edit\', \'' . $users->last()->id . '\', 0)"',
                'title="Edit">',
                'edit-icon',
                '<a wire:click.prevent="rowAction(\'destroy\', \'' . $users->last()->id . '\', 1)"',
                'title="Destroy">',
                'destroy-icon',
                '</tr>',
                '</tbody>',
            ])->assertDontSeeHtml([
                '<a wire:click.prevent="rowAction(\'destroy\', \'' . $users->first()->id . '\', 1)"',
            ]);
    }
}
