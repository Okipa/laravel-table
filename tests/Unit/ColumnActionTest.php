<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractColumnAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\ColumnActions\Toggle;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnActionTest extends TestCase
{
    /** @test */
    public function it_can_set_column_set_action(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        Config::set('laravel-table.icon.active', 'active-icon');
        Config::set('laravel-table.icon.inactive', 'inactive-icon');
        Config::set('laravel-table.icon.display', 'display-icon');
        $users = User::factory()->count(2)->state(new Sequence(
            ['active' => true],
            ['active' => false],
        ))->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                    Column::make('Toggle', 'active')->action(fn() => new Toggle()),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->first()->id . '\', 0)"',
                'class="link-danger p-1"',
                'title="Toggle">',
                'inactive-icon',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'active\', \'' . $users->last()->id . '\', 0)"',
                'class="link-success p-1"',
                'title="Toggle">',
                'active-icon',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'active', $users->first()->id, false)
            ->call('columnAction', 'active', $users->last()->id, false);
        $this->assertFalse($users->first()->fresh()->active);
        $this->assertTrue($users->last()->fresh()->active);
    }

    /** @test */
    public function it_can_display_row_action_conditionally(): void
    {
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        $users = User::factory()->count(2)->state(new Sequence(
            ['active' => true],
            ['active' => false],
        ))->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                    Column::make('Toggle', 'active')
                        ->action(fn() => (new Toggle())->onlyWhen(fn(User $user) => ! Auth::user()->is($user))),
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
            ]);
    }

    /** @test */
    public function it_can_ask_confirmation_before_action_execution(): void
    {
        Date::setTestNow(Date::now()->startOfDay());
        $users = User::factory()->count(2)->state(new Sequence(
            ['email_verified_at' => Date::now()],
            ['email_verified_at' => null],
        ))->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                $action = new class extends AbstractColumnAction {
                    protected function class(Model $model, string $attribute): string|null
                    {
                        return $model->email_verified_at ? 'link-danger p-1' : 'link-success p-1';
                    }

                    protected function icon(Model $model, string $attribute): string
                    {
                        return '<i class="fa-solid fa-envelope fa-fw"></i>';
                    }

                    protected function title(Model $model, string $attribute): string
                    {
                        return $model->email_verified_at ? __('Set Email Unverified') : __('Set Email Verified');
                    }

                    protected function shouldBeConfirmed(): bool
                    {
                        return true;
                    }

                    public function action(Model $model, string $attribute, Component $livewire): void
                    {
                        $model->update(['email_verified_at' => $model->email_verified_at ? null : Date::now()]);
                    }
                };

                return [
                    Column::make('Name'),
                    Column::make('Email Verified')
                        ->action(fn(User $user) => (new $action())
                            ->confirmationMessage('Are you sure you want to set email as '
                                . ($user->email_verified_at ? 'unverified' : 'verified')
                                . ' for user ' . $user->name . '?')
                            ->executedMessage('Email of user ' . $user->name . ' has been set as '
                                . ($user->email_verified_at ? 'unverified' : 'verified') . '.')),
                ];
            }
        };
        Livewire::actingAs($users->first())
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $users->first()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'email_verified\', \'' . $users->first()->id . '\', 1)"',
                'class="link-danger p-1"',
                'title="Set Email Unverified">',
                '<i class="fa-solid fa-envelope fa-fw"></i>',
                'Set Email Unverified',
                '</tr>',
                '<tr wire:key="row-' . $users->last()->id . '" class="border-bottom">',
                '<a wire:click.prevent="columnAction(\'email_verified\', \'' . $users->last()->id . '\', 1)"',
                'class="link-success p-1"',
                'title="Set Email Verified">',
                '<i class="fa-solid fa-envelope fa-fw"></i>',
                'Set Email Verified',
                '</tr>',
                '</tbody>',
            ])
            ->call('columnAction', 'email_verified', $users->first()->id, true)
            ->assertEmitted(
                'table:action:confirm',
                'columnAction',
                'email_verified',
                (string) $users->first()->id,
                'Are you sure you want to set email as unverified for user ' . $users->first()->name . '?'
            )
            ->emit('table:action:confirmed', 'columnAction', 'email_verified', $users->first()->id)
            ->assertEmitted(
                'table:action:executed',
                'Email of user ' . $users->first()->name . ' has been set as unverified.'
            )
            ->call('columnAction', 'email_verified', $users->last()->id, true)
            ->assertEmitted(
                'table:action:confirm',
                'columnAction',
                'email_verified',
                (string) $users->last()->id,
                'Are you sure you want to set email as verified for user ' . $users->last()->name . '?'
            )
            ->emit('table:action:confirmed', 'columnAction', 'email_verified', $users->last()->id)
            ->assertEmitted(
                'table:action:executed',
                'Email of user ' . $users->last()->name . ' has been set as verified.'
            );
        $this->assertNull($users->first()->fresh()->email_verified_at);
        $this->assertTrue(Date::now()->eq($users->last()->fresh()->email_verified_at));
    }
}
