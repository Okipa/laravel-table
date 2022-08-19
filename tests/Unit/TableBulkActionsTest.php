<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\BulkActions\ActivateBulkAction;
use Okipa\LaravelTable\BulkActions\CancelEmailVerificationBulkAction;
use Okipa\LaravelTable\BulkActions\DeactivateBulkAction;
use Okipa\LaravelTable\BulkActions\DestroyBulkAction;
use Okipa\LaravelTable\BulkActions\VerifyEmailBulkAction;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_bulk_actions_dropdown_and_column_when_none_is_defined(): void
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
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertDontSeeHtml([
                '<td class="px-0" colspan="',
                '<th wire:key="bulk-actions" class="align-middle" scope="col">',
                '<input wire:model="selectAll" class="me-1" type="checkbox">',
                '<a id="bulk-actions-dropdown"',
                '<ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $users->first()->id . '">',
                '<input wire:model="selectedModelKeys" type="checkbox" value="' . $users->last()->id . '">',
            ]);
    }

    /** @test */
    public function it_can_set_bulk_actions(): void
    {
        Date::setTestNow();
        $users = User::factory()->count(2)->create([
            'email_verified_at' => null,
            'active' => false,
        ]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    new VerifyEmailBulkAction('email_verified_at'),
                    new CancelEmailVerificationBulkAction('email_verified_at'),
                    new ActivateBulkAction('active'),
                    new DeactivateBulkAction('active'),
                    new DestroyBulkAction(),
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
            'selectedModelKeys' => [(string) $users->first()->id],
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
                '<div class="d-flex align-items-center">',
                '<input wire:model="selectAll" class="me-1" type="checkbox">',
                '<div class="dropdown" title="Bulk Actions" data-bs-toggle="tooltip">',
                '<a id="bulk-actions-dropdown"',
                'class="dropdown-toggle"',
                'type="button"',
                'data-bs-toggle="dropdown"',
                'aria-expanded="false">',
                '</a>',
                '<ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">',
                '<li wire:key="bulk-action-verify-email">',
                '<button wire:click.prevent="bulkAction(\'verify_email\', 1)"',
                'class="dropdown-item"',
                'title="Verify Email (1)"',
                'type="button">',
                'Verify Email (1)',
                '</button>',
                '</li>',
                '<li wire:key="bulk-action-cancel-email-verification">',
                '<button wire:click.prevent="bulkAction(\'cancel_email_verification\', 1)"',
                'class="dropdown-item"',
                'title="Unverify Email (1)"',
                'type="button">',
                'Unverify Email (1)',
                '</button>',
                '</li>',
                '<li wire:key="bulk-action-activate">',
                '<button wire:click.prevent="bulkAction(\'activate\', 1)"',
                'class="dropdown-item"',
                'title="Activate (1)"',
                'type="button">',
                'Activate (1)',
                '</button>',
                '</li>',
                '<li wire:key="bulk-action-deactivate">',
                '<button wire:click.prevent="bulkAction(\'deactivate\', 1)"',
                'class="dropdown-item"',
                'title="Deactivate (1)"',
                'type="button">',
                'Deactivate (1)',
                '</button>',
                '</li>',
                '<li wire:key="bulk-action-destroy">',
                '<button wire:click.prevent="bulkAction(\'destroy\', 1)"',
                'class="dropdown-item"',
                'title="Destroy (1)"',
                'type="button">',
                'Destroy (1)',
                '</button>',
                '</li>',
                '</ul>',
                '</div>',
                '</div>',
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
            ]);
        // Verify Email
        $component->call('bulkAction', 'verify_email', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'verify_email',
                null,
                'Are you sure you want to execute the action Verify Email on the line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'verify_email', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Verify Email has been executed on the line #' . $users->first()->id . '.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'email_verified_at' => Date::now(),
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'email_verified_at' => null,
        ]);
        // Unverify Email
        User::query()->update(['email_verified_at' => Date::now()]);
        $component->call('bulkAction', 'cancel_email_verification', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'cancel_email_verification',
                null,
                'Are you sure you want to execute the action Unverify Email on the line #'
                . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'cancel_email_verification', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Unverify Email has been executed on the line #' . $users->first()->id . '.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'email_verified_at' => null,
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'email_verified_at' => Date::now(),
        ]);
        // Activate
        $component->call('bulkAction', 'activate', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'activate',
                null,
                'Are you sure you want to execute the action Activate on the line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'activate', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Activate has been executed on the line #' . $users->first()->id . '.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'active' => true,
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'active' => false,
        ]);
        // Deactivate
        User::query()->update(['active' => true]);
        $component->call('bulkAction', 'deactivate', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'deactivate',
                null,
                'Are you sure you want to execute the action Deactivate on the line #' . $users->first()->id . '?',
            )
            ->emit('table:action:confirmed', 'bulkAction', 'deactivate', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Deactivate has been executed on the line #' . $users->first()->id . '.'
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->first()->id,
            'active' => false,
        ]);
        $this->assertDatabaseHas(app(User::class)->getTable(), [
            'id' => $users->last()->id,
            'active' => true,
        ]);
        // Destroy
        $component->call('bulkAction', 'destroy', true)
            ->assertEmitted(
                'table:action:confirm',
                'bulkAction',
                'destroy',
                null,
                'Are you sure you want to execute the action Destroy on the line #' . $users->first()->id . '?'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'destroy', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Destroy has been executed on the line #' . $users->first()->id . '.'
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
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    (new DestroyBulkAction())->when(Auth::user()->isNot($user)),
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
                'selectedModelKeys' => User::pluck('id')->map(fn (int $id) => (string) $id)->toArray(),
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
                '<div class="d-flex align-items-center">',
                '<input wire:model="selectAll" class="me-1" type="checkbox">',
                '<div class="dropdown" title="Bulk Actions" data-bs-toggle="tooltip">',
                '<a id="bulk-actions-dropdown"',
                'class="dropdown-toggle"',
                'type="button"',
                'data-bs-toggle="dropdown"',
                'aria-expanded="false">',
                '</a>',
                '<ul class="dropdown-menu" aria-labelledby="bulk-actions-dropdown">',
                '<li wire:key="bulk-action-destroy">',
                '<button wire:click.prevent="bulkAction(\'destroy\', 1)"',
                'class="dropdown-item"',
                'title="Destroy (2)"',
                'type="button">',
                'Destroy (2)',
                '</button>',
                '</li>',
                '</ul>',
                '</div',
                '</div',
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
                'Are you sure you want to execute the action Destroy on the 2 selected lines? The line #' . $user1->id
                . ' does not allow the action Destroy and will not be affected.'
            )
            ->emit('table:action:confirmed', 'bulkAction', 'destroy', null)
            ->assertEmitted(
                'table:action:feedback',
                'The action Destroy has been executed on the 2 selected lines. The line #' . $user1->id
                . ' does not allow the action Destroy and was not affected.',
            );
        $this->assertDatabaseHas(app(User::class)->getTable(), ['id' => $user1->id]);
        $this->assertDatabaseMissing(app(User::class)->getTable(), ['id' => $user2->id]);
        $this->assertDatabaseMissing(app(User::class)->getTable(), ['id' => $user3->id]);
    }

    /** @test */
    public function it_can_override_confirmation_question_and_feedback_message(): void
    {
        $user = User::factory()->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    (new DestroyBulkAction())
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
            'selectedModelKeys' => User::pluck('id')->map(fn (int $id) => (string) $id)->toArray(),
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
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    new DestroyBulkAction(),
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
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    new DestroyBulkAction(),
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
            ->assertSet('selectedModelKeys', $users->pluck('id')->map(fn (int $id) => (string) $id)->toArray())
            ->set('selectAll', false)
            ->assertSet('selectedModelKeys', []);
    }
}
