<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\RowActions\DestroyRowAction;
use Okipa\LaravelTable\RowActions\EditRowAction;
use Okipa\LaravelTable\RowActions\ShowRowAction;
use Okipa\LaravelTable\Table;
use Tests\Models\User;

class TableRowActionsTest extends \Tests\Unit\Bootstrap5\TableRowActionsTest
{
    /** @test */
    public function it_can_set_row_actions(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);
        Config::set('laravel-table.icon.show', 'show-icon');
        Config::set('laravel-table.icon.edit', 'edit-icon');
        Config::set('laravel-table.icon.destroy', 'destroy-icon');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn (User $user) => [
                    new ShowRowAction(route('user.show', $user)),
                    new EditRowAction(route('user.edit', $user)),
                    new DestroyRowAction(),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="row-actions" class="align-middle text-end" scope="col">',
                'Actions',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<td class="align-middle text-end">',
                '<div class="d-flex align-items-center justify-content-end">',
                '<a wire:key="row-action-show-' . $users->first()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_show\', \'' . $users->first()->id . '\', 0)"',
                ' class="link-info p-1"',
                ' href=""',
                ' title="Show"',
                ' data-toggle="tooltip">',
                'show-icon',
                '</a>',
                '<a wire:key="row-action-edit-' . $users->first()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_edit\', \'' . $users->first()->id . '\', 0)"',
                ' class="link-primary p-1"',
                ' href=""',
                ' title="Edit"',
                ' data-toggle="tooltip">',
                'edit-icon',
                '</a>',
                '<a wire:key="row-action-destroy-' . $users->first()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_destroy\', \'' . $users->first()->id . '\', 1)"',
                ' class="link-danger p-1"',
                ' href=""',
                ' title="Destroy"',
                ' data-toggle="tooltip">',
                'destroy-icon',
                '</a>',
                '</div>',
                '</td>',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<td class="align-middle text-end">',
                '<div class="d-flex align-items-center justify-content-end">',
                '<a wire:key="row-action-show-' . $users->last()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_show\', \'' . $users->last()->id . '\', 0)"',
                ' class="link-info p-1"',
                ' href=""',
                ' title="Show"',
                ' data-toggle="tooltip">',
                'show-icon',
                '</a>',
                '<a wire:key="row-action-edit-' . $users->last()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_edit\', \'' . $users->last()->id . '\', 0)"',
                ' class="link-primary p-1"',
                ' href=""',
                ' title="Edit"',
                ' data-toggle="tooltip">',
                'edit-icon',
                '</a>',
                '<a wire:key="row-action-destroy-' . $users->last()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_destroy\', \'' . $users->last()->id . '\', 1)"',
                ' class="link-danger p-1"',
                ' href=""',
                ' title="Destroy"',
                ' data-toggle="tooltip">',
                'destroy-icon',
                '</a>',
                '</div>',
                '</td>',
                '</tr>',
                '</tbody>',
            ])
            ->call('rowAction', 'row_action_show', $users->first()->id, false)
            ->assertNotEmitted('laraveltable:action:feedback')
            ->assertRedirect(route('user.show', $users->first()))
            ->call('rowAction', 'row_action_edit', $users->last()->id, false)
            ->assertNotEmitted('laraveltable:action:feedback')
            ->assertRedirect(route('user.edit', $users->last()))
            ->call('rowAction', 'row_action_destroy', $users->first()->id, true)
            ->assertEmitted(
                'laraveltable:action:confirm',
                'rowAction',
                'row_action_destroy',
                (string) $users->first()->id,
                'Are you sure you want to execute the action Destroy on the line #' . $users->first()->id . '?'
            )
            ->emit('laraveltable:action:confirmed', 'rowAction', 'row_action_destroy', $users->first()->id)
            ->assertEmitted(
                'laraveltable:action:feedback',
                'The action Destroy has been executed on the line #' . $users->first()->id . '.'
            );
        $this->assertDatabaseMissing('users', ['id' => $users->first()->id]);
    }

    /** @test */
    public function it_can_allow_row_action_conditionally(): void
    {
        Config::set('laravel-table.icon.destroy', 'destroy-icon');
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn (User $user) => [
                    (new DestroyRowAction())->when(Auth::user()->isNot($user)),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        Livewire::actingAs($users->first())
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<td class="align-middle text-end">',
                '<div class="d-flex align-items-center justify-content-end">',
                '<a wire:key="row-action-destroy-' . $users->last()->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_destroy\', \'' . $users->last()->id . '\', 1)"',
                ' href=""',
                ' title="Destroy"',
                ' data-toggle="tooltip">',
                'destroy-icon',
                '</a>',
                '</div>',
                '</td>',
                '</tr>',
                '</tbody>',
            ])->assertDontSeeHtml([
                '<a wire:click.prevent="rowAction(\'row_action_destroy\', \'' . $users->first()->id . '\', 1)"',
            ]);
    }

    /** @test */
    public function it_can_override_confirmation_question_and_feedback_message(): void
    {
        Config::set('laravel-table.icon.destroy', 'destroy-icon');
        $user = User::factory()->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn (User $user) => [
                    (new DestroyRowAction())->confirmationQuestion(false)->feedbackMessage(false),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $user->id . '" class="border-bottom">',
                '<td class="align-middle text-end">',
                '<div class="d-flex align-items-center justify-content-end">',
                '<a wire:key="row-action-destroy-' . $user->id . '"',
                ' wire:click.prevent="rowAction(\'row_action_destroy\', \'' . $user->id . '\', 0)"',
                ' href=""',
                ' title="Destroy"',
                ' data-toggle="tooltip">',
                'destroy-icon',
                '</a>',
                '</div>',
                '</td>',
                '</tr>',
                '</tbody>',
            ])
            ->call('rowAction', 'row_action_destroy', $user->id, false)
            ->assertNotEmitted('laraveltable:action:confirm')
            ->assertNotEmitted('laraveltable:action:feedback');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
