<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\ColumnActions\ToggleBooleanColumnAction;
use Okipa\LaravelTable\ColumnActions\ToggleEmailVerifiedColumnAction;
use Okipa\LaravelTable\Table;
use Tests\Models\User;

class ColumnActionTest extends \Tests\Unit\Bootstrap5\ColumnActionTest
{
    /** @test */
    public function it_can_set_column_actions(): void
    {
        Date::setTestNow(Date::now()->startOfDay());
        Config::set('laravel-table.icon.email_verified', 'email-verified-icon');
        Config::set('laravel-table.icon.email_unverified', 'email-unverified-icon');
        Config::set('laravel-table.icon.toggle_on', 'toggle-on-icon');
        Config::set('laravel-table.icon.toggle_off', 'toggle-off-icon');
        $users = User::factory()->count(2)->state(new Sequence(
            ['email_verified_at' => Date::now(), 'active' => true],
            ['email_verified_at' => null, 'active' => false]
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                    Column::make('email_verified_at')
                        ->action(fn () => new ToggleEmailVerifiedColumnAction()),
                    Column::make('active')
                        ->action(fn () => new ToggleBooleanColumnAction()),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<th wire:key="cell-name-' . $users->first()->id . '" class="align-middle" scope="row">',
                $users->first()->name,
                '</th>',
                '<td wire:key="cell-email-verified-at-' . $users->first()->id . '" class="align-middle">',
                '<a wire:key="column-action-email-verified-at-' . $users->first()->id . '"',
                ' wire:click.prevent="columnAction(\'email_verified_at\', \'' . $users->first()->id . '\', 1)"',
                ' class="link-success p-1"',
                ' href=""',
                ' title="Unverify Email"',
                ' data-toggle="tooltip">',
                'email-verified-icon',
                '</a>',
                '</td>',
                '<td wire:key="cell-active-' . $users->first()->id . '" class="align-middle">',
                '<a wire:key="column-action-active-' . $users->first()->id . '"',
                ' wire:click.prevent="columnAction(\'active\', \'' . $users->first()->id . '\', 0)"',
                ' class="link-success p-1"',
                ' href=""',
                ' title="Toggle Off"',
                ' data-toggle="tooltip">',
                'toggle-on-icon',
                '</a>',
                '</td>',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<th wire:key="cell-name-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->name,
                '</th>',
                '<td wire:key="cell-email-verified-at-' . $users->last()->id . '" class="align-middle">',
                '<a wire:key="column-action-email-verified-at-' . $users->last()->id . '"',
                ' wire:click.prevent="columnAction(\'email_verified_at\', \'' . $users->last()->id . '\', 1)"',
                ' class="link-danger p-1"',
                ' href=""',
                ' title="Verify Email"',
                ' data-toggle="tooltip">',
                'email-unverified-icon',
                '</a>',
                '</td>',
                '<td wire:key="cell-active-' . $users->last()->id . '" class="align-middle">',
                '<a wire:key="column-action-active-' . $users->last()->id . '"',
                ' wire:click.prevent="columnAction(\'active\', \'' . $users->last()->id . '\', 0)"',
                ' class="link-danger p-1"',
                ' href=""',
                ' title="Toggle On"',
                ' data-toggle="tooltip">',
                'toggle-off-icon',
                '</a>',
                '</td>',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'active', $users->first()->id, false)
            ->assertEmitted(
                'laraveltable:action:feedback',
                'The action Toggle Off has been executed on the field validation.attributes.active '
                . 'from the line #' . $users->first()->id . '.',
            )
            ->call('columnAction', 'email_verified_at', $users->last()->id, true)
            ->assertEmitted(
                'laraveltable:action:confirm',
                'columnAction',
                'email_verified_at',
                (string) $users->last()->id,
                'Are you sure you want to execute the action Verify Email on the field validation.attributes.email_verified_at from the line #'
                . $users->last()->id . '?'
            )
            ->emit('laraveltable:action:confirmed', 'columnAction', 'email_verified_at', $users->last()->id)
            ->assertEmitted(
                'laraveltable:action:feedback',
                'The action Verify Email has been executed on the field '
                . 'validation.attributes.email_verified_at from the line #' . $users->last()->id . '.'
            );
        $this->assertFalse($users->first()->fresh()->active);
        $this->assertTrue(Date::now()->eq($users->last()->fresh()->email_verified_at));
    }

    /** @test */
    public function it_can_override_confirmation_question_and_feedback_message(): void
    {
        Config::set('laravel-table.icon.email_verified', 'email-verified-icon');
        $user = User::factory()->create(['email_verified_at' => Date::now()]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                    Column::make('email_verified_at')
                        ->action(fn () => (new ToggleEmailVerifiedColumnAction())
                            ->confirmationQuestion(false)
                            ->feedbackMessage(false)),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $user->id . '" class="border-bottom">',
                '<a wire:key="column-action-email-verified-at-' . $user->id . '"',
                ' wire:click.prevent="columnAction(\'email_verified_at\', \'' . $user->id . '\', 0)"',
                ' class="link-success p-1"',
                ' href=""',
                ' title="Unverify Email"',
                ' data-toggle="tooltip">',
                'email-verified-icon',
                '</a>',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'email_verified_at', $user->id, false)
            ->assertNotEmitted('laraveltable:action:confirm')
            ->assertNotEmitted('laraveltable:action:feedback');
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
