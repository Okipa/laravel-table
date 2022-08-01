<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\ColumnActions\ToggleBooleanColumnAction;
use Okipa\LaravelTable\ColumnActions\ToggleEmailVerifiedColumnAction;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnActionTest extends TestCase
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
                    Column::make('Name'),
                    Column::make('Email Verified', 'email_verified_at')
                        ->action(fn () => new ToggleEmailVerifiedColumnAction()),
                    Column::make('Toggle', 'active')
                        ->action(fn () => new ToggleBooleanColumnAction()),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'email_verified_at\', \'' . $users->first()->id . '\', 1)"',
                'class="link-success p-1"',
                'title="Unverify Email"',
                'data-bs-toggle="tooltip">',
                'email-verified-icon',
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->first()->id . '\', 0)"',
                'class="link-success p-1"',
                'title="Toggle Off"',
                'data-bs-toggle="tooltip">',
                'toggle-on-icon',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'email_verified_at\', \'' . $users->last()->id . '\', 1)"',
                'class="link-danger p-1"',
                'title="Verify Email"',
                'data-bs-toggle="tooltip">',
                'email-unverified-icon',
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->last()->id . '\', 0)"',
                'class="link-danger p-1"',
                'title="Toggle On"',
                'data-bs-toggle="tooltip">',
                'toggle-off-icon',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'active', $users->first()->id, false)
            ->assertEmitted(
                'table:action:feedback',
                'The action Toggle Off has been executed on the field validation.attributes.active '
                . 'from the line #' . $users->first()->id . '.',
            )
            ->call('columnAction', 'email_verified_at', $users->last()->id, true)
            ->assertEmitted(
                'table:action:confirm',
                'columnAction',
                'email_verified_at',
                (string) $users->last()->id,
                'Are you sure you want to execute the action Verify Email on the field validation.attributes.email_verified_at from the line #'
                . $users->last()->id . '?'
            )
            ->emit('table:action:confirmed', 'columnAction', 'email_verified_at', $users->last()->id)
            ->assertEmitted(
                'table:action:feedback',
                'The action Verify Email has been executed on the field '
                . 'validation.attributes.email_verified_at from the line #' . $users->last()->id . '.'
            );
        $this->assertFalse($users->first()->fresh()->active);
        $this->assertTrue(Date::now()->eq($users->last()->fresh()->email_verified_at));
    }

    /** @test */
    public function it_can_display_row_action_conditionally(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        $users = User::factory()->count(2)->state(new Sequence(
            ['active' => true],
            ['active' => false],
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
                    Column::make('Name'),
                    Column::make('Toggle', 'active')
                        ->action(fn (User $user) => (new ToggleBooleanColumnAction())->when(Auth::user()->isNot($user))),
                ];
            }
        };
        Livewire::actingAs($users->first())
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->last()->id . '\', 0)"',
                '</tr>',
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->first()->id . '\', 0)"',
                '<td class="align-middle">1</td>',
            ]);
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
                    Column::make('Name'),
                    Column::make('Email Verified', 'email_verified_at')
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
                '<a wire:click.prevent="columnAction(\'email_verified_at\', \'' . $user->id . '\', 0)"',
                'class="link-success p-1"',
                'title="Unverify Email"',
                'data-bs-toggle="tooltip">',
                'email-verified-icon',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'email_verified_at', $user->id, false)
            ->assertNotEmitted('table:action:confirm')
            ->assertNotEmitted('table:action:feedback');
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
