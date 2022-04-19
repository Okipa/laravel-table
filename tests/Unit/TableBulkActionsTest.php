<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\BulkActions\Activate;
use Okipa\LaravelTable\BulkActions\Deactivate;
use Okipa\LaravelTable\BulkActions\Destroy;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableBulkActionsTest extends TestCase
{
    /** @test */
    public function it_can_set_bulk_actions(): void
    {
        $users = User::factory()->count(2)->create(['active' => false]);
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn(User $user) => [
                    new Activate('active'),
                    new Deactivate('active'),
                    new Destroy(),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                ];
            }
        };
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, [
            'config' => $config::class,
            'selectedModelKeys' => [$users->first()->id],
        ])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<td class="px-0" colspan="2">',
                '</td>',
                '</tr>',
                '<tr',
                '<th wire:key="bulk-actions" class="align-middle" scope="col">',
                '<input wire:model="selectAll" type="checkbox">',
                '<div class="dropdown">',
                '<a id="bulk-actions-dropdown"',
                'class="dropdown-toggle"',
                'type="button"',
                'data-bs-toggle="dropdown"',
                'aria-expanded="false">',
                '</a>',
                '<ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">',
                '<li>',
                '<button wire:click.prevent="bulkAction(\'activate\', 1)"',
                'class="dropdown-item"',
                'title="Activate (1)"',
                'type="button">',
                'Activate (1)',
                '</button>',
                '</li>',
                '<li>',
                '<button wire:click.prevent="bulkAction(\'deactivate\', 1)"',
                'class="dropdown-item"',
                'title="Deactivate (1)"',
                'type="button">',
                'Deactivate (1)',
                '</button>',
                '</li>',
                '<li>',
                '<button wire:click.prevent="bulkAction(\'destroy\', 1)"',
                'class="dropdown-item"',
                'title="Destroy (1)"',
                'type="button">',
                'Destroy (1)',
                '</button>',
                '</li>',
                '</ul>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $users->first()->id . '">',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $users->last()->id . '">',
                '</tr>',
                '</tbody>',
            ])
            ->call('bulkAction', 'activate', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'activate',
                null,
                'Are you sure you want to activate the selected line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'activate', null)
            ->assertEmitted(
                'table:action:feedback',
                'The selected line #' . $users->first()->id . ' has been activated.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'active' => true,
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'active' => false,
        ]);
        User::get()->each->update(['active' => true]);
        $component->call('bulkAction', 'deactivate', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'deactivate',
                null,
                'Are you sure you want to deactivate the selected line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'deactivate', null)
            ->assertEmitted(
                'table:action:feedback',
                'The selected line #' . $users->first()->id . ' has been deactivated.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'active' => false,
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'active' => true,
        ]);
        $component->call('bulkAction', 'destroy', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'destroy',
                null,
                'Are you sure you want to destroy the selected line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'destroy', null)
            ->assertEmitted(
                'table:action:feedback',
                'The selected line #' . $users->first()->id . ' has been destroyed.'
            );
        $this->assertDatabaseMissing(app(User::class)->getTable(), ['id' => $users->first()->id]);
        $this->assertDatabaseHas(app(User::class)->getTable(), ['id' => $users->last()->id]);
    }

    /** @test */
    public function it_can_display_bulk_action_conditionally(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn(User $user) => [
                    (new Destroy())->when(Auth::user()->isNot($user)),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                ];
            }
        };
        Livewire::actingAs($user1)
            ->test(\Okipa\LaravelTable\Livewire\Table::class, [
                'config' => $config::class,
                'selectedModelKeys' => User::pluck('id')->toArray(),
            ])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<td class="px-0" colspan="2">',
                '</td>',
                '</tr>',
                '<tr',
                '<th wire:key="bulk-actions" class="align-middle" scope="col">',
                '<input wire:model="selectAll" type="checkbox">',
                '<div class="dropdown">',
                '<a id="bulk-actions-dropdown"',
                'class="dropdown-toggle"',
                'type="button"',
                'data-bs-toggle="dropdown"',
                'aria-expanded="false">',
                '</a>',
                '<ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">',
                '<li>',
                '<button wire:click.prevent="bulkAction(\'destroy\', 1)"',
                'class="dropdown-item"',
                'title="Destroy (2)"',
                'type="button">',
                'Destroy (2)',
                '</button>',
                '</li>',
                '</ul>',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                '<tr wire:key="row-' . $user1->id . '" class="border-bottom">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $user1->id . '">',
                '</tr>',
                '<tr wire:key="row-' . $user2->id . '" class="border-bottom">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $user2->id . '">',
                '</tr>',
                '<tr wire:key="row-' . $user3->id . '" class="border-bottom">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $user3->id . '">',
                '</tr>',
                '</tbody>',
            ])
            ->call('bulkAction', 'destroy', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'destroy',
                null,
                'Are you sure you want to destroy the 2 selected lines? The line #' . $user1->id
                . ' does not allow destruction and will not be affected by this action.'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'destroy', null)
            ->assertEmitted(
                'table:action:feedback',
                '2 selected lines have been destroyed. The line #' . $user1->id
                . ' does not allow destruction and was not affected by this action.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), ['id' => $user1->id]);
        $this->assertDatabaseMissing(app(User::class)->getTable(), ['id' => $user2->id]);
        $this->assertDatabaseMissing(app(User::class)->getTable(), ['id' => $user3->id]);
    }

    /** @test */
    public function it_can_override_confirmation_question_and_feedback_message(): void
    {
        $user = User::factory()->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn(User $user) => [
                    (new Destroy())
                        ->confirmationQuestion(false)
                        ->feedbackMessage(false),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, [
            'config' => $config::class,
            'selectedModelKeys' => User::pluck('id')->toArray(),
        ])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<button wire:click.prevent="bulkAction(\'destroy\', 0)"',
                '</thead>',
            ])
            ->call('bulkAction', 'destroy', false)
            ->assertNotEmitted('table:action:confirm')
            ->assertNotEmitted('table:action:feedback');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_cant_trigger_bulk_action_with_no_selected_line(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn(User $user) => [
                    new Destroy(),
                ]);
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
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<button wire:click.prevent="bulkAction(\'destroy\', 1)"',
                'title="Destroy (0)"',
                'Destroy (0)',
                '</thead>',
            ])
            ->call('bulkAction', 'destroy', true)
            ->assertNotEmitted('table:action:confirm')
            ->emit('table:action:confirmed', 'bulkAction', 'destroy', null)
            ->assertNotEmitted('table:action:feedback');
        $this->assertDatabaseHas(app(User::class)->getTable(), ['id' => $users->first()->id]);
        $this->assertDatabaseHas(app(User::class)->getTable(), ['id' => $users->last()->id]);
    }

    /** @test */
    public function it_can_select_all_lines(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn(User $user) => [
                    new Destroy(),
                ]);
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
            ->set('selectAll', true)
            ->assertSet('selectedModelKeys', $users->pluck('id')->toArray())
            ->set('selectAll', false)
            ->assertSet('selectedModelKeys', []);
    }
}
